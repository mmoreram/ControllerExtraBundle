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
 * Class ObjectManager
 *
 * @Annotation
 */
class ObjectManager extends Annotation
{
    /**
     * @var string
     *
     * class
     */
    protected $class;

    /**
     * @var string
     *
     * Name of the parameter
     */
    protected $name;

    /**
     * Get Class
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
}
