<?php

/**
 * Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 */

namespace Mmoreram\ControllerExtraBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\AbstractManagerRegistry;

use Mmoreram\ControllerExtraBundle\EventListener\Abstracts\AbstractEventListener;
use Mmoreram\ControllerExtraBundle\Annotation\Flush as AnnotationFlush;
use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;


/**
 * FormAnnotationEventListener, an extension of AbstractEventListener
 */
class FlushAnnotationEventListener extends AbstractEventListener
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
     * @param KernelInterface         $kernel   Kernel
     * @param Reader                  $reader   Reader
     * @param AbstractManagerRegistry $doctrine Doctrine
     */
    public function __construct(KernelInterface $kernel, Reader $reader, AbstractManagerRegistry $doctrine)
    {
        parent::__construct($kernel, $reader);

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
     * @param array      $controller        Controller
     * @param Request    $request           Request
     * @param Annotation $annotation        Annotation
     * @param array      $parametersIndexed Parameters indexed
     *
     * @return AbstractEventListener self Object
     */
    public function evaluateAnnotation(array $controller, Request $request, Annotation $annotation, array $parametersIndexed)
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
