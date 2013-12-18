<?php

/**
 * Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 */

namespace Mmoreram\ControllerExtraBundle\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

use Mmoreram\ControllerExtraBundle\Resolver\Interfaces\AnnotationResolverInterface;
use Mmoreram\ControllerExtraBundle\Annotation\Log as AnnotationLog;
use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;


/**
 * LogAnnotationResolver, an implementation of  AnnotationResolverInterface
 */
class LogAnnotationResolver implements AnnotationResolverInterface
{

    /**
     * @var LoggerInterface
     *
     * Logger
     */
    protected $logger;


    /**
     * @var string
     *
     * default level
     */
    protected $defaultLevel;


    /**
     * Construct method
     *
     * @param LoggerInterface $logger Logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * Return container
     *
     * @return LoggerInterface Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }


    /**
     * Set default level name
     *
     * @param string $defaultLevel Default level name
     *
     * @return FlushAnnotationEventListener self Object
     */
    public function setDefaultLevel($defaultLevel)
    {
        $this->defaultLevel = $defaultLevel;

        return $this;
    }


    /**
     * Get default level name
     *
     * @return string Default level
     */
    public function getDefaultLevel()
    {
        return $this->defaultLevel;
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
         * Annotation is only laoded if is typeof AnnotationLog
         */
        if ($annotation instanceof AnnotationLog) {

            $level  = !is_null($annotation->getLevel())
                    ? $annotation->getLevel()
                    : $this->getDefaultLevel();

            /**
             * Logs content, using specified level
             */
            $this->logger = $this
                ->getLogger()
                ->$level($annotation->getMessage());
        }
    }
}
