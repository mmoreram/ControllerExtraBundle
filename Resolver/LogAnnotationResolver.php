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

use Psr\Log\LoggerInterface;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

use Mmoreram\ControllerExtraBundle\Annotation\Annotation;
use Mmoreram\ControllerExtraBundle\Annotation\Log as AnnotationLog;

/**
 * Class LogAnnotationResolver.
 */
class LogAnnotationResolver extends AnnotationResolver
{
    /**
     * @var LoggerInterface
     *
     * Logger
     */
    private $logger;

    /**
     * @var string
     *
     * default level
     */
    private $defaultLevel;

    /**
     * @var string
     *
     * Default execute
     */
    private $defaultExecute;

    /**
     * @var bool
     *
     * Must log
     */
    private $mustLog = false;

    /**
     * @var string
     *
     * Level
     */
    private $level;

    /**
     * @var string
     *
     * Execute
     */
    private $execute;

    /**
     * @var string
     *
     * Value
     */
    private $value;

    /**
     * LogAnnotationResolver constructor.
     *
     * @param LoggerInterface $logger
     * @param string          $defaultLevel
     * @param string          $defaultExecute
     */
    public function __construct(
        LoggerInterface $logger,
        string $defaultLevel,
        string $defaultExecute
    ) {
        $this->logger = $logger;
        $this->defaultLevel = $defaultLevel;
        $this->defaultExecute = $defaultExecute;
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
         * Annotation is only laoded if is typeof AnnotationLog.
         */
        if ($annotation instanceof AnnotationLog) {
            $this->level = $annotation->getLevel()
                ? $annotation->getLevel()
                : $this->defaultLevel;

            $this->execute = $annotation->getExecute()
                ? $annotation->getExecute()
                : $this->defaultExecute;

            $this->mustLog = true;
            $this->value = $annotation->getValue();

            /**
             * Only logs before controller execution if EXEC_PRE or EXEC_BOTH.
             */
            if (in_array($this->execute, [AnnotationLog::EXEC_PRE, AnnotationLog::EXEC_BOTH])) {
                $this->logMessage(
                    $this->logger,
                    $this->level,
                    $this->value
                );
            }
        }
    }

    /**
     * Method executed while loading Controller.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($this->mustLog) {

            /**
             * Only logs before controller execution if EXEC_POST or EXEC_BOTH.
             */
            if (in_array($this->execute, [AnnotationLog::EXEC_POST, AnnotationLog::EXEC_BOTH])) {
                $this->logMessage(
                    $this->logger,
                    $this->level,
                    $this->value
                );
            }
        }
    }

    /**
     * Send value to log.
     *
     * @param LoggerInterface $logger
     * @param string          $level
     * @param string          $value
     */
    private function logMessage(
        LoggerInterface $logger,
        string $level,
        string $value
    ) {
        /**
         * Logs content, using specified level.
         */
        $logger->$level($value);
    }
}
