<?php

/**
 * Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 */

namespace Mmoreram\ControllerExtraBundle\EventListener\Abstracts;

use ReflectionMethod;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\Request;

use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;


/**
 * Abstract Event Listener
 */
abstract class AbstractEventListener
{

    /**
     * @var KernelInterface
     *
     * Kernel
     */
    protected $kernel;


    /**
     * @var Reader
     *
     * Annotation Reader
     */
    protected $reader;


    /**
     * @var boolean
     *
     * Current annotation must be evaluated
     */
    protected $active;


    /**
     * @var array
     *
     * Method parameters indexed
     */
    protected $parametersIndexed = array();


    /**
     * Construct method
     *
     * @param KernelInterface $kernel Kernel
     * @param Reader          $reader Reader
     */
    public function __construct(KernelInterface $kernel, Reader $reader)
    {
        $this->kernel = $kernel;
        $this->reader = $reader;
    }


    /**
     * Return kernel object
     *
     * @return KernelInterface Kernel
     */
    protected function getKernel()
    {
        return $this->kernel;
    }


    /**
     * Return reader
     *
     * @return Reader Reader
     */
    protected function getReader()
    {
        return $this->reader;
    }


    /**
     * Method executed while loading Controller
     *
     * @param FilterControllerEvent $event Filter Controller event
     *
     * @todo place all non-specific data in a service
     */
    public function onKernelController(FilterControllerEvent $event)
    {

        if (!$this->active) {

            return;
        }

        /**
         * Data load
         */
        $controller = $event->getController();

        if (!is_array($controller)) {

            return;
        }

        $request = $event->getRequest();
        $method = new ReflectionMethod($controller[0], $controller[1]);

        /**
         * Method parameteres load.
         * A hash is created to access to all needed parameters with cost O(1)
         */
        $parameters = $method->getParameters();

        foreach ($parameters as $parameter) {

            $this->parametersIndexed[$parameter->getName()] = $parameter;
        }

        /**
         * Annotations load
         */
        $methodAnnotations = $this
            ->getReader()
            ->getMethodAnnotations($method);

        /**
         * Every annotation found is parsed
         */
        foreach ($methodAnnotations as $annotation) {

            if ($annotation instanceof Annotation) {

                $this->evaluateAnnotation($controller, $request, $annotation, $this->parametersIndexed);
            }
        }
    }


    /**
     * Specific annotation evaluation.
     *
     * This method must be implemented in every single EventListener with specific logic
     *
     * @param boolean $active Define if current annotation must be evaluated
     *
     * @return AbstractEventListener self Object
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }


    /**
     * Specific annotation evaluation.
     *
     * This method must be implemented in every single EventListener with specific logic
     *
     * All method code will executed only if specific active flag is true
     *
     * @param array $controller Controller
     * @param Request $request Request
     * @param Annotation $annotation Annotation
     * @param array $parametersIndexed Parameters indexed
     *
     * @return AbstractEventListener self Object
     */
    abstract public function evaluateAnnotation(array $controller, Request $request, Annotation $annotation, array $parametersIndexed);
}
