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
