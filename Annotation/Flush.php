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
 * Flush annotation driver
 *
 * @Annotation
 */
class Flush extends Annotation
{

    /**
     * @var string
     *
     * Manager to use when flushing
     */
    public $manager;


    /**
     * return manager
     *
     * @return string Manager
     */
    public function getManager()
    {
        return $this->manager;
    }
}