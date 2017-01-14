<?php

/*
 * This file is part of the ControllerExtraBundle for Symfony2.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Mmoreram\ControllerExtraBundle\Provider;

use ErrorException;
use Symfony\Component\Debug\Exception\ClassNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

use Mmoreram\ControllerExtraBundle\Annotation\AnnotationWithEntityReference;

/**
 * Class EntityProvider.
 */
final class EntityProvider
{
    /**
     * @var ContainerInterface
     *
     * container
     */
    private $container;

    /**
     * @var KernelInterface
     *
     * Kernel
     */
    private $kernel;

    /**
     * @var string
     *
     * defaultFactoryMethod
     */
    private $defaultFactoryMethod;

    /**
     * @var bool
     *
     * defaultFactoryStatic
     */
    private $defaultFactoryStatic;

    /**
     * construct method.
     *
     * @param ContainerInterface $container
     * @param KernelInterface    $kernel
     * @param string             $defaultFactoryMethod
     * @param string             $defaultFactoryStatic
     */
    public function __construct(
        ContainerInterface $container,
        KernelInterface $kernel,
        string $defaultFactoryMethod,
        string $defaultFactoryStatic
    ) {
        $this->container = $container;
        $this->kernel = $kernel;
        $this->defaultFactoryMethod = $defaultFactoryMethod;
        $this->defaultFactoryStatic = $defaultFactoryStatic;
    }

    /**
     * Class provider, given several formats.
     *
     * Accepted formats:
     *
     *   class = "my.class.parameter",
     *   class = "\My\Class\Namespace",
     *   class = "MmoreramCustomBundle:User",
     *   factory = {
     *       "factory": "Mmoreram\ControllerExtraBundle\Factory\EntityFactory",
     *       "factory": "my_factory_service",
     *       "method": "create",
     *       "static": true
     *   }
     *
     * @param AnnotationWithEntityReference $annotation
     *
     * @return object|null
     */
    public function create(AnnotationWithEntityReference $annotation)
    {
        return null !== $annotation->getFactory()
            ? $this->evaluateEntityInstanceFactory($annotation->getFactory())
            : $this->evaluateEntityInstanceNamespace($annotation->getNamespace());
    }

    /**
     * Evaluates entity instance using a factory.
     *
     * @param array $factory
     *
     * @return object Entity instance
     *
     * @throws InvalidArgumentException          if the service is not defined
     * @throws ServiceCircularReferenceException When a circular reference is detected
     * @throws ServiceNotFoundException          When the service is not found
     */
    public function evaluateEntityInstanceFactory(array $factory)
    {
        if (!isset($factory['class'])) {
            throw new InvalidArgumentException();
        }

        $factoryReference = $factory['class'];

        $factoryMethod = isset($factory['method'])
            ? $factory['method']
            : $this->defaultFactoryMethod;

        $factoryStatic = isset($factory['static'])
            ? (bool) $factory['static']
            : (bool) $this->defaultFactoryStatic;

        $factory = class_exists($factoryReference)
            ? (
            $factoryStatic
                ? $factoryReference
                : new $factoryReference()
            )
            : $this
                ->container
                ->get($factoryReference);

        return $factoryStatic
            ? $factory::$factoryMethod()
            : $factory->$factoryMethod();
    }

    /**
     * Evaluates entity instance using the namespace.
     *
     * @param string $namespace
     *
     * @return object
     *
     * @throws ClassNotFoundException if class is not found
     */
    public function evaluateEntityInstanceNamespace(string $namespace)
    {
        $namespace = $this->evaluateEntityNamespace($namespace);

        return new $namespace();
    }

    /**
     * Evaluates entity instance using the namespace.
     *
     * @param string $namespace
     *
     * @return string
     *
     * @throws ClassNotFoundException if class is not found
     */
    public function evaluateEntityNamespace(string $namespace)
    {
        /**
         * Trying to generate new entity given that the class is the entity
         * namespace.
         */
        if (class_exists($namespace)) {
            return $namespace;
        }

        /**
         * Trying to generate new entity given that the namespace is defined in
         * as a Container parameter.
         */
        $container = $this->container;
        if (
            $container->hasParameter($namespace) &&
            class_exists($container->getParameter($namespace))
        ) {
            $namespaceParameter = $container->getParameter($namespace);

            return $namespaceParameter;
        }

        $resolvedNamespace = explode(':', $namespace, 2);
        $bundles = $this->kernel->getBundles();

        /**
         * Trying to get entity using Doctrine short format.
         *
         * MyBundle:MyEntity
         *
         * To accept this format, entities must be at Entity/ folder in the
         * bundle root dir
         *
         * /MyBundle/Entity/MyEntity
         *
         * If entity definition is wrong, throw exception
         * If bundle not exists or is not actived, throw Exception
         */
        if (
        !(
            isset($resolvedNamespace[0]) &&
            isset($bundles[$resolvedNamespace[0]]) &&
            $bundles[$resolvedNamespace[0]] instanceof Bundle &&
            isset($resolvedNamespace[1])
        )
        ) {
            throw new ClassNotFoundException(
                $namespace,
                new ErrorException()
            );
        }

        /**
         * @var Bundle $bundle
         */
        $bundle = $bundles[$resolvedNamespace[0]];
        $bundleNamespace = $bundle->getNamespace();
        $namespace = $bundleNamespace . '\\Entity\\' . $resolvedNamespace[1];

        if (!class_exists($namespace)) {
            throw new ClassNotFoundException(
                $namespace,
                new ErrorException()
            );
        }

        /**
         * Otherwise, assume that class is namespace of class.
         */

        return $namespace;
    }
}
