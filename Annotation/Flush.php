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
     * @var entity
     *
     * Entity from Request ParameterBag to flush
     */
    public $entity;

    /**
     * return manager
     *
     * @return string Manager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * return entity
     *
     * @return string Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
