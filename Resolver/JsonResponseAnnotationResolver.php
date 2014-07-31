<?php

/**
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
     * @param integer $defaultStatus  Default status
     * @param array   $defaultHeaders Default headers
     */
    public function __construct($defaultStatus, array $defaultHeaders)
    {
        $this->defaultStatus = $defaultStatus;
        $this->defaultHeaders = $defaultHeaders;
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
     * Get response headers
     *
     * @return integer Response headers
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
            $response = JsonResponse::create(
                $result,
                $this->getStatus(),
                $this->getHeaders()
            );

            $event->setResponse($response);
        }
    }
}
