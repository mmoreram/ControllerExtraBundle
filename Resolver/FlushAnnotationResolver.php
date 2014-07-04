<?php

/**
 * This file is part of the Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 */

namespace Mmoreram\ControllerExtraBundle\Resolver;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use ReflectionMethod;

use Mmoreram\ControllerExtraBundle\Resolver\Interfaces\AnnotationResolverInterface;
use Mmoreram\ControllerExtraBundle\Annotation\Flush as AnnotationFlush;
use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;

/**
 * FormAnnotationResolver, an implementation of  AnnotationResolverInterface
 */
class FlushAnnotationResolver implements AnnotationResolverInterface
{
    /**
     * @var AbstractManagerRegistry
     *
     * Doctrine object
     */
    protected $doctrine;

    /**
     * @var ObjectManager
     *
     * Manager
     */
    protected $manager;

    /**
     * @var string
     *
     * default manager
     */
    protected $defaultManager;

    /**
     * @var array
     *
     * Set of entities from Request ParameterBag to flush
     */
    public $entities;

    /**
     * @var boolean
     *
     * Must flush boolean
     */
    protected $mustFlush = false;

    /**
     * Construct method
     *
     * @param AbstractManagerRegistry $doctrine       Doctrine
     * @param string                  $defaultManager Default manager
     */
    public function __construct(AbstractManagerRegistry $doctrine, $defaultManager)
    {
        $this->doctrine = $doctrine;
        $this->defaultManager = $defaultManager;
    }

    /**
     * Get Doctrine object
     *
     * @return AbstractManagerRegistry Doctrine instance
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * Get default manager name
     *
     * @return string Default manager
     */
    public function getDefaultManager()
    {
        return $this->defaultManager;
    }

    /**
     * Get Manager object
     *
     * @return ObjectManager Manager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Get entities
     *
     * @return ArrayCollection Entities to flush
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * Return if manager must be flushed
     *
     * @return boolean Manager must be flushed
     */
    public function getMustFlush()
    {
        return $this->mustFlush;
    }

    /**
     * Specific annotation evaluation.
     *
     * @param Request          $request    Request
     * @param Annotation       $annotation Annotation
     * @param ReflectionMethod $method     Method
     *
     * @return FlushAnnotationResolver self Object
     */
    public function evaluateAnnotation(
        Request $request,
        Annotation $annotation,
        ReflectionMethod $method
    )
    {
        /**
         * Annotation is only laoded if is typeof AnnotationFlush
         */
        if ($annotation instanceof AnnotationFlush) {

            $managerName = $annotation->getManager()
                ? : $this->getDefaultManager();

            /**
             * Loading locally desired Doctrine manager
             */
            $this->manager = $this
                ->getDoctrine()
                ->getManager($managerName);

            /**
             * Set locally entities to flush. If null, flush all
             */
            $this->entities = new ArrayCollection;
            $entity = $annotation->getEntity();
            $entities = is_array($entity)
                ? $entity
                : array($entity);

            /**
             * For every entity defined, we try to get it from Request Attributes
             */
            foreach ($entities as $entityName) {

                if ($request->attributes->has($entityName)) {

                    $this->entities[] = $request->attributes->get($entityName);
                }
            }

            /**
             * If we have not found any entity to flush, or any has been defined.
             * In this case, flush all
             */
            if ($this->entities->isEmpty()) {

                $this->entities = null;
            }

            /**
             * In this case, manager must be flushed after controller logic
             */
            $this->mustFlush = true;
        }

        return $this;
    }

    /**
     * Method executed while loading Controller
     *
     * @param FilterResponseEvent $event Filter Response event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        /**
         * Only flushes if exists AnnotationFlush as a controller annotations
         */
        if ($this->getMustFlush()) {

            /**
             * Flushing manager
             */
            $this
                ->getManager()
                ->flush($this->getEntities());
        }
    }
}
