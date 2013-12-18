<?php

/**
 * Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 */

namespace Mmoreram\ControllerExtraBundle\Annotation;

use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;

/**
 * Log annotation driver
 *
 * @Annotation
 */
class Log extends Annotation
{

    /**
     * @var string
     *
     * Emergency level
     */
    const LVL_EMERG = 'emergency';


    /**
     * @var string
     *
     * Alert level
     */
    const LVL_ALERT = 'alert';


    /**
     * @var string
     *
     * Critical level
     */
    const LVL_CRIT = 'critical';


    /**
     * @var string
     *
     * Error level
     */
    const LVL_ERR = 'error';


    /**
     * @var string
     *
     * Warning level
     */
    const LVL_WARNING = 'warning';


    /**
     * @var string
     *
     * Info level
     */
    const LVL_INFO = 'info';


    /**
     * @var string
     *
     * Debug level
     */
    const LVL_DEBUG = 'debug';


    /**
     * @var string
     *
     * Log level
     */
    const LVL_LOG = 'log';


    /**
     * @var string
     *
     * Level
     */
    public $level;


    /**
     * @var string
     *
     * Meessage
     */
    public $message;


    /**
     * return manager
     *
     * @return string Manager
     */
    public function getLevel()
    {
        return $this->level;
    }


    /**
     * return message
     *
     * @return string message
     */
    public function getMessage()
    {
        return $this->message;
    }
}