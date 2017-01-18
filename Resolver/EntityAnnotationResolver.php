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

namespace Mmoreram\ControllerExtraBundle\Resolver;

use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Doctrine\ORM\EntityNotFoundException;
use ReflectionMethod;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

use Mmoreram\ControllerExtraBundle\Annotation\Annotation;
use Mmoreram\ControllerExtraBundle\Annotation\LoadEntity;
use Mmoreram\ControllerExtraBundle\Provider\EntityProvider;
use Mmoreram\ControllerExtraBundle\Provider\Provider;

/**
 * Class EntityAnnotationResolver.
 */
class EntityAnnotationResolver extends AnnotationResolver
{
    /**
     * @var ContainerInterface
     *
     * Container
     */
    private $container;

    /**
     * @var AbstractManagerRegistry
     *
     * Doctrine object
     */
    private $doctrine;

    /**
     * @var EntityProvider
     *
     * Entity provider
     */
    private $entityProvider;

    /**
     * @var Provider
     *
     * Provider collector
     */
    private $providerCollector;

    /**
     * @var string
     *
     * Default field name
     */
    private $defaultName;

    /**
     * @var bool
     *
     * Default persist value
     */
    private $defaultPersist;

    /**
     * @var bool
     *
     * Mapping fallback
     */
    private $mappingFallback;

    /**
     * Construct method.
     *
     * @param ContainerInterface      $container
     * @param AbstractManagerRegistry $doctrine
     * @param EntityProvider          $entityProvider
     * @param Provider                $providerCollector
     * @param string                  $defaultName
     * @param bool                    $defaultPersist
     * @param bool                    $mappingFallback
     */
    public function __construct(
        ContainerInterface $container,
        AbstractManagerRegistry $doctrine,
        EntityProvider $entityProvider,
        Provider $providerCollector,
        string $defaultName,
        bool $defaultPersist,
        bool $mappingFallback = false
    ) {
        $this->container = $container;
        $this->doctrine = $doctrine;
        $this->entityProvider = $entityProvider;
        $this->providerCollector = $providerCollector;
        $this->defaultName = $defaultName;
        $this->defaultPersist = $defaultPersist;
        $this->mappingFallback = $mappingFallback;
    }

    /**
     * Specific annotation evaluation.
     *
     * @param Request          $request
     * @param Annotation       $annotation
     * @param ReflectionMethod $method
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
        if ($annotation instanceof LoadEntity) {

            /**
             * Creating new instance of desired entity.
             */
            $entityNamespace = $this
                ->entityProvider
                ->evaluateEntityNamespace($annotation->getNamespace());

            /**
             * Tries to get a mapped instance of this entity. If not found,
             * return null.
             */
            $entity = $this->evaluateMapping($annotation, $entityNamespace);

            if (!$entity instanceof $entityNamespace) {
                $entity = $this
                    ->entityProvider
                    ->create($annotation);
            }

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
    }

    /**
     * Resolve doctrine mapping and return entity given or mapped instance.
     *
     * @param LoadEntity $annotation
     * @param string     $entityNamespace
     *
     * @return null|object
     *
     * @throws EntityNotFoundException
     */
    private function evaluateMapping(
        LoadEntity $annotation,
        string $entityNamespace
    ) {
        if (!empty($annotation->getMapping())) {
            $mapping = $annotation->getMapping();
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
                $parameterValue = $this
                    ->providerCollector
                    ->provide($mappingValue);

                /**
                 * Defined field is not found in current route, and we have
                 * enabled the "mapping fallback" setting. In that case we
                 * assume that the mapping definition is wrong, and we return
                 * the entity itself.
                 */
                if ($mappingFallback && ($parameterValue === $mappingValue)) {
                    return null;
                }

                $mapping[$mappingKey] = $parameterValue;
            }

            $instance = $this
                ->resolveRepositoryLookup(
                    $annotation,
                    $entityNamespace,
                    $mapping
                );

            if (!$instance instanceof $entityNamespace) {
                throw new EntityNotFoundException(
                    'Entity of type ' . $entityNamespace . ' with mapping ' .
                    json_encode($mapping) . ' was not found.'
                );
            }

            return $instance;
        }

        return null;
    }

    /**
     * Evaluate setters.
     *
     * @param ParameterBag $attributes
     * @param object       $entity
     * @param array        $setters
     */
    private function evaluateSetters(
        ParameterBag $attributes,
        $entity,
        array $setters
    ) {
        foreach ($setters as $method => $value) {
            $entity->$method($attributes->get($value));
        }
    }

    /**
     * Resolve repository lookup.
     *
     * @param LoadEntity $annotation
     * @param string     $entityClass
     * @param array      $mapping
     *
     * @return object|null
     */
    private function resolveRepositoryLookup(
        LoadEntity $annotation,
        string $entityClass,
        array $mapping
    ) {
        $annotationRepository = $annotation->getRepository();
        $annotationHasRepository = !is_null($annotationRepository) && is_array($annotationRepository);
        if ($annotationHasRepository) {
            $class = $annotation->getRepository()['class'];
            $repository = $this
                    ->container
                    ->has($class)
                ? $this
                    ->container
                    ->get($class)
                : new $class();
        } else {
            $repository = $this
                ->doctrine
                ->getManagerForClass($entityClass)
                ->getRepository($entityClass);
        }

        $method = $annotationHasRepository && isset($annotationRepository['method'])
            ? $annotationRepository['method']
            : 'findOneBy';

        return $repository->$method($mapping);
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
     * @param LoadEntity $annotation
     * @param object     $entity
     */
    private function resolvePersist(LoadEntity $annotation, $entity)
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
    }
}
