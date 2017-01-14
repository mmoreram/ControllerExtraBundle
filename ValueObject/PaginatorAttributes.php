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

namespace Mmoreram\ControllerExtraBundle\ValueObject;

/**
 * Class PaginatorAttributes.
 */
final class PaginatorAttributes
{
    /**
     * @var int
     *
     * total pages
     */
    private $totalPages;

    /**
     * @var int
     *
     * total elements
     */
    private $totalElements;

    /**
     * @var int
     *
     * total page
     */
    private $currentPage;

    /**
     * @var int
     *
     * number of elements per page
     */
    private $limitPerPage;

    /**
     * PaginatorAttributes constructor.
     *
     * @param int $totalPages
     * @param int $totalElements
     * @param int $currentPage
     * @param int $limitPerPage
     */
    public function __construct(
        int $totalPages,
        int $totalElements,
        int $currentPage,
        int $limitPerPage
    ) {
        $this->totalPages = $totalPages;
        $this->totalElements = $totalElements;
        $this->currentPage = $currentPage;
        $this->limitPerPage = $limitPerPage;
    }

    /**
     * Get TotalElements.
     *
     * @return int
     */
    public function getTotalElements() : int
    {
        return $this->totalElements;
    }

    /**
     * Get TotalPages.
     *
     * @return int
     */
    public function getTotalPages() : int
    {
        return $this->totalPages;
    }

    /**
     * Get CurrentPage.
     *
     * @return int
     */
    public function getCurrentPage() : int
    {
        return $this->currentPage;
    }

    /**
     * Get LimitPerPage.
     *
     * @return int
     */
    public function getLimitPerPage() : int
    {
        return $this->limitPerPage;
    }
}
