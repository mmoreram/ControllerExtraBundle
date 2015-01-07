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
 * Class Paginator
 *
 * @Annotation
 */
class Paginator extends Annotation
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
    public $name;

    /**
     * @var integer
     *
     * page
     */
    protected $page;

    /**
     * @var integer
     *
     * limit
     */
    protected $limit;

    /**
     * @var string
     *
     * orderBy
     */
    protected $orderBy;

    /**
     * @var array
     *
     * left joins
     */
    protected $leftJoins;

    /**
     * @var array
     *
     * inner joins
     */
    protected $innerJoins;

    /**
     * @var array
     *
     * wheres
     */
    protected $wheres;

    /**
     * @var array
     *
     * Not nulls
     */
    protected $notNulls;

    /**
     * @var string
     *
     * Attributes
     */
    protected $attributes;

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

    /**
     * Get Page
     *
     * @return int Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Get OrderBy
     *
     * @return array OrderBy
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * Get Limit
     *
     * @return int Limit
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Get InnerJoins
     *
     * @return array InnerJoins
     */
    public function getInnerJoins()
    {
        return $this->innerJoins;
    }

    /**
     * Get LeftJoins
     *
     * @return array LeftJoins
     */
    public function getLeftJoins()
    {
        return $this->leftJoins;
    }

    /**
     * Get Wheres
     *
     * @return array Wheres
     */
    public function getWheres()
    {
        return $this->wheres;
    }

    /**
     * Get NotNulls
     *
     * @return array NotNulls
     */
    public function getNotNulls()
    {
        return $this->notNulls;
    }

    /**
     * Get Attributes
     *
     * @return string Attributes
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
