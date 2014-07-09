<?php

/**
 * This file is part of the ControllerExtraBundle for Symfony2.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace Mmoreram\ControllerExtraBundle\Provider;

use Symfony\Component\Debug\Exception\ClassNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;
use ErrorException;

/**
 * Class EntityProvider
 */
class EntityProvider
{
    /**
     * @var ContainerInterface
     *
     * container
     */
    protected $container;

    /**
     * @var KernelInterface
     *
     * Kernel
     */
    protected $kernel;

    /**
     * @var string
     *
     * defaultFactoryMethod
     */
    protected $defaultFactoryMethod;

    /**
     * @var bool
     *
     * defaultFactoryStatic
     */
    protected $defaultFactoryStatic;

    /**
     * construct method
     *
     * @param ContainerInterface $container            Container
     * @param KernelInterface    $kernel               Kernel
     * @param string             $defaultFactoryMethod Default factory method
     * @param string             $defaultFactoryStatic Default factory static
     */
    public function __construct(
        ContainerInterface $container,
        KernelInterface $kernel,
        $defaultFactoryMethod,
        $defaultFactoryStatic
    )
    {
        $this->container = $container;
        $this->kernel = $kernel;
        $this->defaultFactoryMethod = $defaultFactoryMethod;
        $this->defaultFactoryStatic = $defaultFactoryStatic;
    }

    /**
     * Class provider, given several formats
     *
     * Accepted formats:
     *
     *   class = "my.class.parameter",
     *   class = "\My\Class\Namespace",
     *   class = "MmoreramCustomBundle:User",
     *   class = {
     *       "factory": "Mmoreram\ControllerExtraBundle\Factory\EntityFactory",
     *       "factory": "my_factory_service",
     *       "method": "create",
     *       "static": true
     *   }
     *
     * @param mixed $class Formatted class
     *
     * @return Object|null Entity if exists, otherwise null
     */
    public function provide($class)
    {
        return is_array($class)
            ? $this->evaluateEntityInstanceFactory($class)
            : $this->evaluateEntityInstanceNamespace($class);
    }

    /**
     * Evaluates entity instance using a factory
     *
     * @param array $class Class
     *
     * @return Object Entity instance
     *
     * @throws InvalidArgumentException          if the service is not defined
     * @throws ServiceCircularReferenceException When a circular reference is detected
     * @throws ServiceNotFoundException          When the service is not found
     */
    public function evaluateEntityInstanceFactory(array $class)
    {
        if (!isset($class['factory'])) {

            throw new InvalidArgumentException;
        }

        $factoryClass = $class['factory'];

        $factoryMethod = isset($class['method'])
            ? $class['method']
            : $this->defaultFactoryMethod;

        $factoryStatic = isset($class['static'])
            ? (boolean) $class['static']
            : (boolean) $this->defaultFactoryStatic;

        $factory = class_exists($factoryClass)
            ? (
            $factoryStatic
                ? $factoryClass
                : new $factoryClass
            )
            : $this
                ->container
                ->get($factoryClass);

        return $factoryStatic
            ? $factory::$factoryMethod()
            : $factory->$factoryMethod();
    }

    /**
     * Evaluates entity instance using the namespace
     *
     * @param string $class Class
     *
     * @return Object Entity instance
     *
     * @throws ClassNotFoundException if class is not found
     */
    public function evaluateEntityInstanceNamespace($class)
    {
        /**
         * Trying to generate new entity given that the class is the entity
         * namespace
         */
        if (class_exists($class)) {
            return new $class();
        }

        /**
         * Trying to generate new entity given that the namespace is defined in
         * as a Container parameter
         */
        try {
            $classParameter = $this
                ->container
                ->getParameter($class);
            if ($classParameter && class_exists($classParameter)) {
                return new $classParameter;
            }

        } catch (InvalidArgumentException $exception) {

        }

        $namespace = explode(':', $class, 2);
        $bundles = $this->kernel->getBundles();

        /**
         * Trying to get entity using Doctrine short format
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
            isset($namespace[0]) &&
            isset($bundles[$namespace[0]]) &&
            $bundles[$namespace[0]] instanceof Bundle &&
            isset($namespace[1])
        )
        ) {

            throw new ClassNotFoundException(
                $class,
                new ErrorException
            );
        }

        /**
         * @var Bundle $bundle
         */
        $bundle = $bundles[$namespace[0]];
        $bundleNamespace = $bundle->getNamespace();
        $entityNamespace = $bundleNamespace . '\\Entity\\' . $namespace[1];

        if (!class_exists($entityNamespace)) {

            throw new ClassNotFoundException(
                $entityNamespace,
                new ErrorException
            );
        }

        /**
         * Otherwise, assume that class is namespace of class
         */

        return new $entityNamespace;
    }
}
