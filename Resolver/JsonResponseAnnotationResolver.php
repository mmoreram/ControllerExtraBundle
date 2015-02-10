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

use ReflectionMethod;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;
use Mmoreram\ControllerExtraBundle\Annotation\JsonResponse as AnnotationJsonResponse;
use Mmoreram\ControllerExtraBundle\Resolver\Interfaces\AnnotationResolverInterface;

/**
 * FormAnnotationResolver, an implementation of  AnnotationResolverInterface
 */
class JsonResponseAnnotationResolver implements AnnotationResolverInterface
{
    /**
     * @var integer
     *
     * Status
     */
    protected $defaultStatus;

    /**
     * @var integer
     *
     * Error status
     */
    protected $defaultErrorStatus;

    /**
     * @var array
     *
     * Headers
     */
    protected $defaultHeaders;

    /**
     * @var integer
     *
     * Status
     */
    protected $status;

    /**
     * @var array
     *
     * Headers
     */
    protected $headers;

    /**
     * @var boolean
     *
     * Return Json response
     */
    protected $returnJson = false;

    /**
     * Construct method
     *
     * @param integer $defaultStatus      Default status
     * @param integer $defaultErrorStatus Default error status
     * @param array   $defaultHeaders     Default headers
     */
    public function __construct($defaultStatus, $defaultErrorStatus, array $defaultHeaders)
    {
        $this->defaultStatus      = $defaultStatus;
        $this->defaultErrorStatus = $defaultErrorStatus;
        $this->defaultHeaders     = $defaultHeaders;
    }

    /**
     * Get return Json
     *
     * @return boolean Return Json
     */
    public function getReturnJson()
    {
        return $this->returnJson;
    }

    /**
     * Get default response status
     *
     * @return integer Default Response status
     */
    public function getDefaultStatus()
    {
        return $this->defaultStatus;
    }

    /**
     * Get default error response status
     *
     * @return integer Default error Response status
     */
    public function getDefaultErrorStatus()
    {
        return $this->defaultErrorStatus;
    }

    /**
     * Get default response headers
     *
     * @return integer Default Response headers
     */
    public function getDefaultHeaders()
    {
        return $this->defaultHeaders;
    }

    /**
     * Get response status
     *
     * @return integer Response status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set response status
     *
     * @param integer $status The status response to set
     *
     * @return integer Response status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get response headers
     *
     * @return array Response headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Specific annotation evaluation.
     *
     * @param Request          $request    Request
     * @param Annotation       $annotation Annotation
     * @param ReflectionMethod $method     Method
     *
     * @return JsonResponseAnnotationResolver self Object
     */
    public function evaluateAnnotation(
        Request $request,
        Annotation $annotation,
        ReflectionMethod $method
    )
    {
        /**
         * Annotation is only laoded if is typeof WorkAnnotation
         */
        if ($annotation instanceof AnnotationJsonResponse) {

            /**
             * If JsonResponse annotation, set to true for future events
             *
             * Also saves all needed info from annotation
             */
            $this->returnJson = true;
            $this->status = $annotation->getStatus() ?: $this->getDefaultStatus();
            $this->headers = $annotation->getHeaders() ?: $this->getDefaultHeaders();
        }

        return $this;
    }

    /**
     * Method executed while loading Controller
     *
     * @param GetResponseForControllerResultEvent $event Event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        /**
         * Only flushes if exists AnnotationFlush as a controller annotations
         */
        if ($this->getReturnJson()) {

            $result = $event->getControllerResult();

            if ($result instanceof \Exception) {
                if ($result instanceof HttpExceptionInterface) {
                    $this->setStatus($result->getStatusCode());
                } else {
                    $this->setStatus($this->getDefaultErrorStatus());
                }
                $result = array('message' => $result->getMessage());
            }

            $response = JsonResponse::create(
                $result,
                $this->getStatus(),
                $this->getHeaders()
            );

            $event->setResponse($response);
        }
    }

    /**
     * Method executed when uncaught exception is launched
     *
     * @param GetResponseForExceptionEvent $event Event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($this->getReturnJson()) {

            $exception = $event->getException();

            if ($exception instanceof HttpExceptionInterface) {
                $this->setStatus($exception->getStatusCode());
            } else {
                $this->setStatus($this->getDefaultErrorStatus());
            }
            $result = array('message' => $exception->getMessage());

            $response = JsonResponse::create(
                $result,
                $this->getStatus(),
                $this->getHeaders()
            );

            $event->setResponse($response);
        }
    }
}
