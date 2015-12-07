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

namespace Mmoreram\ControllerExtraBundle\ValueObject;

/**
 * Class PaginatorAttributes.
 */
class PaginatorAttributes
{
    /**
     * @var int
     *
     * total pages
     */
    protected $totalPages;

    /**
     * @var int
     *
     * total elements
     */
    protected $totalElements;

    /**
     * @var int
     *
     * total page
     */
    protected $currentPage;

    /**
     * @var int
     *
     * number of elements per page
     */
    protected $limitPerPage;

    /**
     * Sets TotalElements.
     *
     * @param int $totalElements TotalElements
     *
     * @return PaginatorAttributes Self object
     */
    public function setTotalElements($totalElements)
    {
        $this->totalElements = $totalElements;

        return $this;
    }

    /**
     * Get TotalElements.
     *
     * @return int TotalElements
     */
    public function getTotalElements()
    {
        return $this->totalElements;
    }

    /**
     * Sets TotalPages.
     *
     * @param int $totalPages TotalPages
     *
     * @return PaginatorAttributes Self object
     */
    public function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;

        return $this;
    }

    /**
     * Get TotalPages.
     *
     * @return int TotalPages
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * Sets CurrentPage.
     *
     * @param int $currentPage CurrentPage
     *
     * @return PaginatorAttributes Self object
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    /**
     * Get CurrentPage.
     *
     * @return int CurrentPage
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Sets LimitPerPage.
     *
     * @param int $limitPerPage
     *
     * @return PaginatorAttributes Self object
     */
    public function setLimitPerPage($limitPerPage)
    {
        $this->limitPerPage = $limitPerPage;

        return $this;
    }

    /**
     * Get LimitPerPage.
     *
     * @return int LimitPerPage
     */
    public function getLimitPerPage()
    {
        return $this->limitPerPage;
    }
}
