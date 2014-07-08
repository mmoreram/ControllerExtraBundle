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
 * Entity annotation driver
 *
 * @Annotation
 */
class Entity extends Annotation
{
    /**
     * @var string
     *
     * Namespace of entity in a short namespace mode.
     */
    public $class;

    /**
     * @var string
     *
     * Name of the parameter
     */
    public $name;

    /**
     * @var array
     *
     * Mapping
     */
    public $mapping;

    /**
     * @var array
     *
     * Setters
     */
    public $setters = array();

    /**
     * @var boolean
     *
     * Persist entity
     */
    public $persist;

    /**
     * return class
     *
     * @return string Class
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * return name
     *
     * @return string Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * return mapping
     *
     * @return array Mapping
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * return setters
     *
     * @return array Setters
     */
    public function getSetters()
    {
        return $this->setters;
    }

    /**
     * return persist
     *
     * @return boolean persist
     */
    public function getPersist()
    {
        return $this->persist;
    }
}
