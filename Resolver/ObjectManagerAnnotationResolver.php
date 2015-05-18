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
use Symfony\Component\HttpFoundation\Request;

use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;
use Mmoreram\ControllerExtraBundle\Annotation\ObjectManager as AnnotationObjectManager;
use Mmoreram\ControllerExtraBundle\Exceptions\EntityNotFoundException;
use Mmoreram\ControllerExtraBundle\Provider\EntityProvider;
use Mmoreram\ControllerExtraBundle\Resolver\Interfaces\AnnotationResolverInterface;

/**
 * Class ObjectManagerAnnotationResolver
 */
class ObjectManagerAnnotationResolver implements AnnotationResolverInterface
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
     * @var string
     *
     * Default field name
     */
    protected $defaultName;

    /**
     * Construct method
     *
     * @param AbstractManagerRegistry $doctrine       Doctrine
     * @param EntityProvider          $entityProvider Entity provider
     * @param string                  $defaultName    Default name
     */
    public function __construct(
        AbstractManagerRegistry $doctrine,
        EntityProvider $entityProvider,
        $defaultName
    ) {
        $this->doctrine = $doctrine;
        $this->entityProvider = $entityProvider;
        $this->defaultName = $defaultName;
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
         * Annotation is only laoded if is typeof AnnotationEntity
         */
        if ($annotation instanceof AnnotationObjectManager) {

            /**
             * Creating new instance of desired entity
             */
            $entity = $this
                ->entityProvider
                ->provide($annotation->getClass());

            $objectManager = $this
                ->doctrine
                ->getManagerForClass(get_class($entity));

            /**
             * Get the parameter name. If not defined, is set as defined in
             * parameters
             */
            $parameterName = $annotation->getName()
                ?: $this->defaultName;

            $request->attributes->set(
                $parameterName,
                $objectManager
            );
        }

        return $this;
    }
}
