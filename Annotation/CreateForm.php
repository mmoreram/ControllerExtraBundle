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

namespace Mmoreram\ControllerExtraBundle\Annotation;

/**
 * Class CreateForm.
 *
 * @Annotation
 * @Target({"METHOD"})
 */
final class CreateForm extends Annotation
{
    /**
     * @var string
     *
     * Name of the parameter
     */
    protected $name;

    /**
     * @var string
     *
     * Name of form. This value can refer to a namespace or a service alias
     */
    protected $class;

    /**
     * @var string
     *
     * Entity from Request ParameterBag to use where building form
     */
    protected $entity;

    /**
     * @var bool
     *
     * Handle request
     */
    protected $handleRequest = false;

    /**
     * @var bool
     *
     * Validates submited form if Request is handled.
     * Name of field to set result
     */
    protected $validate = false;

    /**
     * return name.
     *
     * @return null|string
     */
    public function getName() : ? string
    {
        return $this->name;
    }

    /**
     * return class.
     *
     * @return null|string
     */
    public function getClass() : ? string
    {
        return $this->class;
    }

    /**
     * return entity.
     *
     * @return string
     */
    public function getEntity() : ? string
    {
        return $this->entity;
    }

    /**
     * return handle request.
     *
     * @return bool
     */
    public function getHandleRequest() : bool
    {
        return $this->handleRequest;
    }

    /**
     * return validate value.
     *
     * @return bool
     */
    public function getValidate() : bool
    {
        return $this->validate;
    }
}
