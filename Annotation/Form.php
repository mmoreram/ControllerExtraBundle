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
 * Form annotation driver.
 *
 * @Annotation
 */
class Form extends Annotation
{
    /**
     * @var string
     *
     * Name of the parameter
     */
    public $name;

    /**
     * @var string
     *
     * Name of form. This value can refer to a namespace or a service alias
     */
    public $class;

    /**
     * @var entity
     *
     * Entity from Request ParameterBag to use where building form
     */
    public $entity;

    /**
     * @var bool
     *
     * Handle request
     */
    public $handleRequest = false;

    /**
     * @var validate
     *
     * Validates submited form if Request is handled.
     * Name of field to set result
     */
    public $validate = false;

    /**
     * return name.
     *
     * @return string Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * return class.
     *
     * @return string Class
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * return entity.
     *
     * @return string Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * return handle request.
     *
     * @return string Handle Request
     */
    public function getHandleRequest()
    {
        return $this->handleRequest;
    }

    /**
     * return validate value.
     *
     * @return string Validate param name
     */
    public function getValidate()
    {
        return $this->validate;
    }
}
