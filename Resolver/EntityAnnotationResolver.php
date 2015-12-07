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

namespace Mmoreram\ControllerExtraBundle\Resolver;

use Doctrine\Common\Persistence\AbstractManagerRegistry;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;
use Mmoreram\ControllerExtraBundle\Annotation\Entity as AnnotationEntity;
use Mmoreram\ControllerExtraBundle\Exceptions\EntityNotFoundException;
use Mmoreram\ControllerExtraBundle\Provider\EntityProvider;
use Mmoreram\ControllerExtraBundle\Provider\RequestParameterProvider;
use Mmoreram\ControllerExtraBundle\Resolver\Interfaces\AnnotationResolverInterface;

/**
 * EntityAnnotationResolver, an implementation of AnnotationResolverInterface.
 */
class EntityAnnotationResolver implements AnnotationResolverInterface
{
    /**
     * @var AbstractManagerRegistry
     *
     * Doctrine object
     */
    protected $doctrine;

    /**
     * @var EntityProvider
     *
     * Entity provider
     */
    protected $entityProvider;

    /**
     * @var RequestParameterProvider
     *
     * Request parameter provider
     */
    protected $requestParameterProvider;

    /**
     * @var string
     *
     * Default field name
     */
    protected $defaultName;

    /**
     * @var bool
     *
     * Default persist value
     */
    protected $defaultPersist;

    /**
     * @var bool
     *
     * Mapping fallback
     */
    protected $mappingFallback;

    /**
     * Construct method.
     *
     * @param AbstractManagerRegistry  $doctrine                  Doctrine
     * @param EntityProvider           $entityProvider            Entity provider
     * @param RequestParameterProvider $requestParametersProvider Request parameter provider
     * @param string                   $defaultName               Default name
     * @param bool                     $defaultPersist            Default persist
     * @param bool                     $mappingFallback           Mapping fallback
     */
    public function __construct(
        AbstractManagerRegistry $doctrine,
        EntityProvider $entityProvider,
        RequestParameterProvider $requestParametersProvider,
        $defaultName,
        $defaultPersist,
        $mappingFallback = false
    ) {
        $this->doctrine = $doctrine;
        $this->entityProvider = $entityProvider;
        $this->requestParametersProvider = $requestParametersProvider;
        $this->defaultName = $defaultName;
        $this->defaultPersist = $defaultPersist;
        $this->mappingFallback = $mappingFallback;
    }

    /**
     * Specific annotation evaluation.
     *
     * @param Request          $request    Request
     * @param Annotation       $annotation Annotation
     * @param ReflectionMethod $method     Method
     *
     * @return EntityAnnotationResolver self Object
     *
     * @throws EntityNotFoundException
     */
    public function evaluateAnnotation(
        Request $request,
        Annotation $annotation,
        ReflectionMethod $method
    ) {
        /**
         * Annotation is only loaded if is typeof AnnotationEntity.
         */
        if ($annotation instanceof AnnotationEntity) {

            /**
             * Creating new instance of desired entity.
             */
            $entity = $this
                ->entityProvider
                ->provide($annotation->getClass());

            /**
             * Tries to get a mapped instance of this entity.
             * If not mapped, just return old new created.
             */
            $entity = $this->evaluateMapping($annotation, $entity);

            /**
             * Persists entity if defined.
             */
            $this->resolvePersist($annotation, $entity);

            /**
             * If is decided this entity has to be persisted into manager.
             */
            $this->evaluateSetters(
                $request->attributes,
                $entity,
                $annotation->getSetters()
            );

            /**
             * Get the parameter name. If not defined, is set as defined in
             * parameters.
             */
            $parameterName = $annotation->getName()
                ?: $this->defaultName;

            $request->attributes->set(
                $parameterName,
                $entity
            );
        }

        return $this;
    }

    /**
     * Resolve doctrine mapping.
     *
     * @param AnnotationEntity $annotation Annotation
     * @param object           $entity     Entity
     *
     * @return object Entity given or mapped instance
     *
     * @throws EntityNotFoundException Entity was intended to be mapped but not
     *                                 exists
     */
    public function evaluateMapping(AnnotationEntity $annotation, $entity)
    {
        if (is_array($annotation->getMapping())) {
            $mapping = $annotation->getMapping();
            $requestParametersProvider = $this->requestParametersProvider;
            $mappingFallback = !is_null($annotation->getMappingFallback())
                ? $annotation->getMappingFallback()
                : $this->mappingFallback;

            /**
             * Each value of the mapping array is computed and analyzed.
             *
             * If the format is something like %value%, this service will
             * look for the real request attribute value
             */
            foreach ($mapping as $mappingKey => $mappingValue) {
                $parameterValue = $requestParametersProvider->getParameterValue($mappingValue);

                /**
                 * Defined field is not found in current route, and we have
                 * enabled the "mapping fallback" setting. In that case we
                 * assume that the mapping definition is wrong, and we return
                 * the entity itself.
                 */
                if ($mappingFallback && ($parameterValue === $mappingValue)) {
                    return $entity;
                }

                $mapping[$mappingKey] = $parameterValue;
            };

            $entityClass = get_class($entity);
            $instance = $this
                ->doctrine
                ->getManagerForClass($entityClass)
                ->getRepository($entityClass)
                ->findOneBy($mapping);

            if (!($instance instanceof $entityClass)) {
                $notFoundException = $annotation->getNotFoundException();
                if (!empty($notFoundException)) {
                    $exceptionClassName = $notFoundException['exception'];
                    throw new $exceptionClassName($notFoundException['message']);
                }

                throw new EntityNotFoundException(
                    'Entity of type ' . $entityClass . ' with mapping ' .
                    json_encode($mapping) . ' was not found.'
                );
            }

            return $instance;
        }

        return $entity;
    }

    /**
     * Evaluate setters.
     *
     * @param ParameterBag $attributes Request attributes
     * @param object       $entity     Entity
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
     * Persist block.
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
     * @param object           $entity     Entity
     *
     * @return EntityAnnotationResolver self Object
     */
    protected function resolvePersist(AnnotationEntity $annotation, $entity)
    {
        /**
         * Persist block.
         *
         * This block defines if entity must be persisted using desired
         * manager.
         *
         * Given the entity we can find which manager manages it
         *
         * Same logic in perisist option. This variable is defined in bundle
         * parameters and can be overwritten there. Can also be defined in
         * every single annotation
         */

        /**
         * Get the persist variable. If not defined, is set as defined in
         * parameters.
         */
        $persist = !is_null($annotation->getPersist())
            ? $annotation->getPersist()
            : $this->defaultPersist;

        if ($persist) {

            /**
             * Loading locally desired Doctrine manager.
             */
            $this
                ->doctrine
                ->getManagerForClass(get_class($entity))
                ->persist($entity);
        }

        return $this;
    }
}
