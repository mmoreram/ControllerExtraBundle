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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

use Mmoreram\ControllerExtraBundle\Annotation\Annotation;
use Mmoreram\ControllerExtraBundle\Annotation\Flush as AnnotationFlush;

/**
 * Class FlushAnnotationResolver.
 */
class FlushAnnotationResolver extends AnnotationResolver
{
    /**
     * @var AbstractManagerRegistry
     *
     * Doctrine object
     */
    private $doctrine;

    /**
     * @var ObjectManager
     *
     * Manager
     */
    private $manager;

    /**
     * @var string
     *
     * default manager
     */
    private $defaultManager;

    /**
     * @var array
     *
     * Set of entities from Request ParameterBag to flush
     */
    private $entities;

    /**
     * @var bool
     *
     * Must flush boolean
     */
    private $mustFlush = false;

    /**
     * Construct method.
     *
     * @param AbstractManagerRegistry $doctrine
     * @param string                  $defaultManager
     */
    public function __construct(
        AbstractManagerRegistry $doctrine,
        string $defaultManager
    ) {
        $this->doctrine = $doctrine;
        $this->defaultManager = $defaultManager;
    }

    /**
     * Specific annotation evaluation.
     *
     * @param Request          $request
     * @param Annotation       $annotation
     * @param ReflectionMethod $method
     */
    public function evaluateAnnotation(
        Request $request,
        Annotation $annotation,
        ReflectionMethod $method
    ) {
        /**
         * Annotation is only laoded if is typeof AnnotationFlush.
         */
        if ($annotation instanceof AnnotationFlush) {
            $managerName = $annotation->getManager()
                ?: $this->defaultManager;

            /**
             * Loading locally desired Doctrine manager.
             */
            $this->manager = $this
                ->doctrine
                ->getManager($managerName);

            /**
             * Set locally entities to flush. If null, flush all.
             */
            $this->entities = new ArrayCollection();
            $entity = $annotation->getEntity();
            $entities = is_array($entity)
                ? $entity
                : [$entity];

            /**
             * For every entity defined, we try to get it from Request Attributes.
             */
            foreach ($entities as $entityName) {
                if ($request->attributes->has($entityName)) {
                    $this->entities[] = $request->attributes->get($entityName);
                }
            }

            /**
             * If we have not found any entity to flush, or any has been defined.
             * In this case, flush all.
             */
            if ($this->entities->isEmpty()) {
                $this->entities = null;
            }

            /**
             * In this case, manager must be flushed after controller logic.
             */
            $this->mustFlush = true;
        }
    }

    /**
     * Method executed while loading Controller.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        /**
         * Only flushes if exists AnnotationFlush as a controller annotations.
         */
        if ($this->mustFlush) {

            /**
             * Flushing manager.
             */
            $this
                ->manager
                ->flush($this->entities);
        }
    }
}
