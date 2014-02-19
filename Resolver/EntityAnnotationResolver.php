<?php

/**
 * This file is part of the Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mmoreram\ControllerExtraBundle\Resolver;

use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use ReflectionMethod;

use Mmoreram\ControllerExtraBundle\Resolver\Interfaces\AnnotationResolverInterface;
use Mmoreram\ControllerExtraBundle\Annotation\Entity as AnnotationEntity;
use Mmoreram\ControllerExtraBundle\Exceptions\EntityNotFoundException;
use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;

/**
 * EntityAnnotationResolver, an implementation of AnnotationResolverInterface
 */
class EntityAnnotationResolver implements AnnotationResolverInterface
{

    /**
     * @var Doctrine
     *
     * Doctrine object
     */
    protected $doctrine;

    /**
     * @var array
     *
     * Kernel bundles list
     */
    protected $kernelBundles;

    /**
     * @var string
     *
     * Default field name
     */
    protected $defaultName;

    /**
     * @var string
     *
     * default manager
     */
    protected $defaultManager;

    /**
     * @var boolean
     *
     * Default persist value
     */
    protected $defaultPersist;

    /**
     * Construct method
     *
     * @param AbstractManagerRegistry $doctrine       Doctrine
     * @param array                   $kernelBundles  Kernel bundles list
     * @param string                  $defaultName    Default name
     * @param string                  $defaultManager Default manager
     * @param boolean                 $defaultPersist Default persist
     */
    public function __construct(AbstractManagerRegistry $doctrine, array $kernelBundles, $defaultName, $defaultManager, $defaultPersist)
    {
        $this->doctrine = $doctrine;
        $this->kernelBundles = $kernelBundles;
        $this->defaultName = $defaultName;
        $this->defaultManager = $defaultManager;
        $this->defaultPersist = $defaultPersist;
    }

    /**
     * Get doctrine
     *
     * @return AbstractManagerRegistry Doctrine
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * Get default manager name
     *
     * @return array Kernel bundles
     */
    public function getKernelBundles()
    {
        return $this->kernelBundles;
    }

    /**
     * Get default name
     *
     * @return string Default name
     */
    public function getDefaultName()
    {
        return $this->defaultName;
    }

    /**
     * Get default manager
     *
     * @return string Default manager
     */
    public function getDefaultManager()
    {
        return $this->defaultManager;
    }

    /**
     * Get default persist value
     *
     * @return boolean Default persist
     */
    public function getDefaultPersist()
    {
        return $this->defaultPersist;
    }

    /**
     * Specific annotation evaluation.
     *
     * @param Request          $request    Request
     * @param Annotation       $annotation Annotation
     * @param ReflectionMethod $method     Method
     */
    public function evaluateAnnotation(Request $request, Annotation $annotation, ReflectionMethod $method)
    {

        /**
         * Annotation is only laoded if is typeof AnnotationEntity
         */
        if ($annotation instanceof AnnotationEntity) {

            $namespace = explode(':', $annotation->getClass(), 2);
            $kernelBundles = $this->getKernelBundles();

            /**
             * If entity definition is wrong, throw exception
             * If bundle not exists or is not actived, throw Exception
             */
            if (
                    !isset($namespace[0]) ||
                    !isset($kernelBundles[$namespace[0]])||
                    !isset($namespace[1])) {

                throw new EntityNotFoundException;
            }

            $bundle = $kernelBundles[$namespace[0]];
            $bundleNamespace = $bundle->getNamespace();
            $entityNamespace = $bundleNamespace . '\\Entity\\' . $namespace[1];

            if (!class_exists($entityNamespace)) {

                throw new EntityNotFoundException;
            }

            /**
             * Creating new instance of desired entity
             */
            $entity = new $entityNamespace();

            $this->resolvePersist($annotation, $entity);

            /**
             * If is decided this entity has to be persisted into manager
             */

            $this->evaluateSetters(
                $request->attributes,
                $entity,
                $annotation->getSetters()
            );

            /**
             * Get the parameter name. If not defined, is set as defined in
             * parameters
             */
            $parameterName = $annotation->getName() ?: $this->getDefaultName();

            $request->attributes->set(
                $parameterName,
                $entity
            );
        }
    }

    /**
     * Evaluate setters
     *
     * @param ParameterBag $attributes Request attributes
     * @param Object       $entity     Entity
     * @param array        $setters    Array of setters
     *
     * @return EntityAnnotationResolver self Object
     */
    public function evaluateSetters(ParameterBag $attributes, $entity, array $setters)
    {
        foreach ($setters as $method => $value) {

            $entity->$method($attributes->get($value));
        }

        return $this;
    }

    /**
     * Persist block
     *
     * This block defines if entity must be persisted using desired
     * manager.
     *
     * This manager is defined as default in bundle parameters, but can
     * be overwritten in each annotation
     *
     * Same logic in perisist option. This variable is defined in bundle
     * parameters and can be overwritten there. Can also be defined in
     * every single annotation
     *
     * @param AnnotationEntity $annotation Annotation
     * @param Object           $entity     Entity
     *
     * @return EntityAnnotationResolver self Object
     */
    protected function resolvePersist(AnnotationEntity $annotation, $entity)
    {
        /**
         * Persist block
         *
         * This block defines if entity must be persisted using desired
         * manager.
         *
         * This manager is defined as default in bundle parameters, but can
         * be overwritten in each annotation
         *
         * Same logic in perisist option. This variable is defined in bundle
         * parameters and can be overwritten there. Can also be defined in
         * every single annotation
         */

        /**
         * Get the persist variable. If not defined, is set as defined in
         * parameters
         */
        $persist = $annotation->getPersist() ?: $this->getDefaultPersist();

        if ($persist) {

            $managerName = $annotation->getManager() ?: $this->getDefaultManager();

            /**
             * Loading locally desired Doctrine manager
             */
            $this
                ->getDoctrine()
                ->getManager($managerName)
                ->persist($entity);
        }

        return $this;
    }
}
