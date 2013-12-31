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