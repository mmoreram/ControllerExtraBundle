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
class Paginator extends Annotation
{

    /**
     * @var string
     *
     * Entity
     */
    public $entity;


    /**
     * @var string
     *
     * Number of Results per page
     */
    public $number;


    /**
     * @var string
     *
     * Page
     */
    public $page;


    /**
     * @var string
     *
     * OrderBy field in Route
     */
    public $orderByField;


    /**
     * @var array
     *
     * Mapper for OrderBy field
     */
    public $orderByFieldMapper = array();


    /**
     * @var string
     *
     * OrderBy mode ( asc or desc )
     */
    public $orderByMode;


    /**
     * @var array
     *
     * Mapper for OrderBy mode
     */
    public $orderByModeMapper = array();
}