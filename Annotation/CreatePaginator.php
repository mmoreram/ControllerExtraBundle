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
 * Class CreatePaginator.
 *
 * @Annotation
 * @Target({"METHOD"})
 */
final class CreatePaginator extends Annotation
{
    /**
     * @var string
     *
     * Entity namespace
     */
    protected $entityNamespace;

    /**
     * @var string
     *
     * Name of the parameter
     */
    protected $name;

    /**
     * @var int
     *
     * page
     */
    protected $page;

    /**
     * @var int
     *
     * limit
     */
    protected $limit;

    /**
     * @var array
     *
     * orderBy
     */
    protected $orderBy = [];

    /**
     * @var array
     *
     * left joins
     */
    protected $leftJoins = [];

    /**
     * @var array
     *
     * inner joins
     */
    protected $innerJoins = [];

    /**
     * @var array
     *
     * wheres
     */
    protected $wheres = [];

    /**
     * @var array
     *
     * Not nulls
     */
    protected $notNulls = [];

    /**
     * @var string
     *
     * Attributes
     */
    protected $attributes;

    /**
     * Get EntityNamespace.
     *
     * @return null|string
     */
    public function getEntityNamespace() : ? string
    {
        return $this->entityNamespace;
    }

    /**
     * return name.
     *
     * @return null|string Name
     */
    public function getName() : ? string
    {
        return $this->name;
    }

    /**
     * Get Page.
     *
     * @return null|int|string Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Get Limit.
     *
     * @return null|int|string Limit
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Get OrderBy.
     *
     * @return array OrderBy
     */
    public function getOrderBy() : array
    {
        return $this->orderBy;
    }

    /**
     * Get InnerJoins.
     *
     * @return array InnerJoins
     */
    public function getInnerJoins() : array
    {
        return $this->innerJoins;
    }

    /**
     * Get LeftJoins.
     *
     * @return array LeftJoins
     */
    public function getLeftJoins() : array
    {
        return $this->leftJoins;
    }

    /**
     * Get Wheres.
     *
     * @return array Wheres
     */
    public function getWheres() : array
    {
        return $this->wheres;
    }

    /**
     * Get NotNulls.
     *
     * @return array NotNulls
     */
    public function getNotNulls() : array
    {
        return $this->notNulls;
    }

    /**
     * Get Attributes.
     *
     * @return null|string Attributes
     */
    public function getAttributes() : ? string
    {
        return $this->attributes;
    }
}
