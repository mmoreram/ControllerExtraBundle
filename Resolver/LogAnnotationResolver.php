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

use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

use Mmoreram\ControllerExtraBundle\Resolver\Interfaces\AnnotationResolverInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
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
     * @var string
     *
     * Default execute
     */
    protected $defaultExecute;


    /**
     * @var boolean
     *
     * Must log
     */
    protected $mustLog = false;


    /**
     * @var string
     *
     * Level
     */
    protected $level;


    /**
     * @var string
     *
     * Execute
     */
    protected $execute;


    /**
     * @var string
     *
     * Message
     */
    protected $message;


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
     * Set default execute name
     *
     * @param string $defaultExecute Default execute value
     *
     * @return FlushAnnotationEventListener self Object
     */
    public function setDefaultExecute($defaultExecute)
    {
        $this->defaultExecute = $defaultExecute;

        return $this;
    }


    /**
     * Get default execute value
     *
     * @return string Default execute
     */
    public function getDefaultExecute()
    {
        return $this->defaultExecute;
    }


    /**
     * Get must log
     *
     * @return boolean Must log
     */
    public function getMustLog()
    {
        return $this->mustLog;
    }


    /**
     * Get level
     *
     * @return boolean Level
     */
    public function getLevel()
    {
        return $this->level;
    }


    /**
     * Get execute
     *
     * @return string Execute
     */
    public function getExecute()
    {
        return $this->execute;
    }


    /**
     * Get message
     *
     * @return string Message
     */
    public function getMessage()
    {
        return $this->message;
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

            $this->level    = !is_null($annotation->getLevel())
                            ? $annotation->getLevel()
                            : $this->getDefaultLevel();

            $this->execute  = !is_null($annotation->getExecute())
                            ? $annotation->getExecute()
                            : $this->getDefaultExecute();

            $this->mustLog = true;
            $this->message = $annotation->getMessage();

            /**
             * Only logs before controller execution if EXEC_PRE or EXEC_BOTH
             */
            if (in_array($this->getExecute(), array(AnnotationLog::EXEC_PRE, AnnotationLog::EXEC_BOTH))) {

                $this->logMessage($this->getLogger(), $this->getLevel(), $this->getMessage());
            }
        }
    }


    /**
     * Method executed while loading Controller
     *
     * @param FilterResponseEvent $event Filter Response event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {

        if ($this->getMustLog()) {

            /**
             * Only logs before controller execution if EXEC_POST or EXEC_BOTH
             */
            if (in_array($this->getExecute(), array(AnnotationLog::EXEC_POST, AnnotationLog::EXEC_BOTH))) {

                $this->logMessage($this->getLogger(), $this->getLevel(), $this->getMessage());
            }
        }
    }


    /**
     * Send message to log
     *
     * @param LoggerInterface $logger  Logger
     * @param string          $level   Level
     * @param string          $message Message
     *
     * @return LogAnnotationResolver self Object
     */
    public function logMessage(LoggerInterface $logger, $level, $message)
    {
            /**
             * Logs content, using specified level
             */
            $logger->$level($message);

            return $this;
    }
}
