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

use Mmoreram\ControllerExtraBundle\EventListener\Abstracts\AbstractEventListener;
use Mmoreram\ControllerExtraBundle\Annotation\Flush as AnnotationFlush;
use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;


/**
 * FormAnnotationEventListener, an extension of AbstractEventListener
 */
class FlushAnnotationEventListener extends AbstractEventListener
{

    /**
     * @var ObjectManager
     *
     * EntityManager
     */
    protected $entityManager;


    /**
     * @var boolean
     *
     * Must flush boolean
     */
    protected $mustFlush = false;


    /**
     * Construct method
     *
     * @param KernelInterface $kernel       Kernel
     * @param Reader          $reader       Reader
     * @param ObjectManager   $entityManager Entity Manager
     */
    public function __construct(KernelInterface $kernel, Reader $reader, ObjectManager $entityManager)
    {
        parent::__construct($kernel, $reader);

        $this->entityManager = $entityManager;
    }


    /**
     * Specific annotation evaluation.
     *
     * @param array $controller Controller
     * @param Request $request Request
     * @param Annotation $annotation Annotation
     *
     * @return AbstractEventListener self Object
     */
    public function evaluateAnnotation(array $controller, Request $request, Annotation $annotation)
    {

        /**
         * Annotation is only laoded if is typeof AnnotationFlush
         */
        if ($annotation instanceof AnnotationFlush) {

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

        if ($this->mustFlush) {

            $this->entityManager->flush();
        }
    }
}
