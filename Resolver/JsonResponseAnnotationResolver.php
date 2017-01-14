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

use Exception;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

use Mmoreram\ControllerExtraBundle\Annotation\Annotation;
use Mmoreram\ControllerExtraBundle\Annotation\ToJsonResponse;

/**
 * Class JsonResponseAnnotationResolver.
 */
class JsonResponseAnnotationResolver extends AnnotationResolver
{
    /**
     * @var int
     *
     * Status
     */
    private $defaultStatus;

    /**
     * @var int
     *
     * Error status
     */
    private $defaultErrorStatus;

    /**
     * @var array
     *
     * Headers
     */
    private $defaultHeaders;

    /**
     * @var int
     *
     * Status
     */
    private $status;

    /**
     * @var array
     *
     * Headers
     */
    private $headers;

    /**
     * @var bool
     *
     * Return Json response
     */
    private $returnJson = false;

    /**
     * Construct method.
     *
     * @param int   $defaultStatus
     * @param int   $defaultErrorStatus
     * @param array $defaultHeaders
     */
    public function __construct(
        int $defaultStatus,
        int $defaultErrorStatus,
        array $defaultHeaders
    ) {
        $this->defaultStatus = $defaultStatus;
        $this->defaultErrorStatus = $defaultErrorStatus;
        $this->defaultHeaders = $defaultHeaders;
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
         * Annotation is only loaded if is typeof WorkAnnotation.
         */
        if ($annotation instanceof ToJsonResponse) {

            /**
             * If JsonResponse annotation, set to true for future events.
             *
             * Also saves all needed info from annotation
             */
            $this->returnJson = true;
            $this->status = $annotation->getStatus() ?: $this->defaultStatus;
            $this->headers = $annotation->getHeaders() ?: $this->defaultHeaders;
        }
    }

    /**
     * Method executed while loading Controller.
     *
     * @param GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $this->createJsonResponseIfNeeded(
            $event,
            $event->getControllerResult()
        );
    }

    /**
     * Method executed when uncaught exception is launched.
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $this->createJsonResponseIfNeeded(
            $event,
            $event->getException()
        );
    }

    /**
     * Create new Json Response if needed.
     *
     * @param GetResponseEvent $event
     * @param mixed            $result
     */
    private function createJsonResponseIfNeeded(
        GetResponseEvent $event,
        $result
    ) {
        /**
         * Only flushes if exists AnnotationFlush as a controller annotations.
         */
        if ($this->returnJson) {
            if ($result instanceof Exception) {
                $this->status = $result instanceof HttpExceptionInterface
                    ? $result->getStatusCode()
                    : $this->defaultErrorStatus;

                $result = [
                    'code' => $result->getCode(),
                    'namespace' => get_class($result),
                    'message' => $result->getMessage(),
                ];
            }

            $response = JsonResponse::create(
                $result,
                $this->status,
                $this->headers
            );

            $event->setResponse($response);
        }
    }
}
