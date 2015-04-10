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

namespace Mmoreram\ControllerExtraBundle\Annotation;

use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;

/**
 * Post annotation driver
 *
 * @Annotation
 */
class Post extends Annotation
{
    /**
     * @var string
     *
     * The parameter path
     */
    public $path;

    /**
     * @var null|string
     *
     * The name that the parameter will get
     */
    public $name = null;

    /**
     * @var mixed
     *
     * The default value if the parameter is not set
     */
    public $default = null;

    /**
     * @var bool
     *
     * If true, a path like foo[bar] will find deeper items
     */
    public $deep = false;

    /**
     * Posts the path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets the path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Posts the name
     *
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Posts the default
     *
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Sets the default
     *
     * @param mixed $default The default value
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }

    /**
     * Is deep
     *
     * @return mixed
     */
    public function isDeep()
    {
        return $this->deep;
    }

    /**
     * Sets is deep
     *
     * @param boolean $deep
     */
    public function setDeep($deep)
    {
        $this->deep = $deep;
    }
}
