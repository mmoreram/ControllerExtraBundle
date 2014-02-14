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
     * @var Doctrine
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
     * @var boolean
     *
     * Must flush boolean
     */
    protected $mustFlush = false;

    /**
     * Construct method
     *
     * @param AbstractManagerRegistry $doctrine Doctrine
     */
    public function __construct(AbstractManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
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
     * Get Manager object
     *
     * @return ObjectManager Manager
     */
    public function getManager()
    {
        return $this->manager;
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
     * Set default manager name
     *
     * @param string $defaultManager Default manager name
     *
     * @return FlushAnnotationEventListener self Object
     */
    public function setDefaultManager($defaultManager)
    {
        $this->defaultManager = $defaultManager;

        return $this;
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
     * Specific annotation evaluation.
     *
     * @param Request          $request    Request
     * @param Annotation       $annotation Annotation
     * @param ReflectionMethod $method     Method
     */
    public function evaluateAnnotation(Request $request, Annotation $annotation, ReflectionMethod $method)
    {

        /**
         * Annotation is only laoded if is typeof AnnotationFlush
         */
        if ($annotation instanceof AnnotationFlush) {

            $managerName = !is_null($annotation->getManager())
                         ? $annotation->getManager()
                         : $this->getDefaultManager();

            /**
             * Loading locally desired Doctrine manager
             */
            $this->manager = $this
                ->getDoctrine()
                ->getManager($managerName);

            /**
             * In this case, manager must be flushed after controller logic
             */
            $this->mustFlush = true;
        }
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
                ->flush();
        }
    }
}
