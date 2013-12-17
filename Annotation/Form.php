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
 * Form annotation driver
 *
 * @Annotation
 */
class Form extends Annotation
{

    /**
     * @var string
     *
     * Name of form. This value can refer to a namespace or a service alias
     */
    public $name;


    /**
     * @var string
     *
     * Variable where to put generated object
     */
    public $variable;


    /**
     * @var entity
     *
     * Entity from paramconverter process to use where building form
     */
    public $entity;


    /**
     * @var boolean
     *
     * Handle request
     */
    public $handleRequest = false;
}