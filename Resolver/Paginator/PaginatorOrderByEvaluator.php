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

namespace Mmoreram\ControllerExtraBundle\Resolver\Paginator;

use Doctrine\ORM\QueryBuilder;

use Mmoreram\ControllerExtraBundle\Annotation\CreatePaginator;
use Mmoreram\ControllerExtraBundle\Provider\Provider;

/**
 * Class PaginatorOrderByEvaluator.
 */
class PaginatorOrderByEvaluator implements PaginatorEvaluator
{
    /**
     * @var Provider
     *
     * Provider collector
     */
    private $providerCollector;

    /**
     * Construct.
     *
     * @param Provider $providerCollector
     */
    public function __construct(Provider $providerCollector)
    {
        $this->providerCollector = $providerCollector;
    }

    /**
     * Evaluates inner joins.
     *
     * @param QueryBuilder    $queryBuilder
     * @param CreatePaginator $annotation
     */
    public function evaluate(
        QueryBuilder $queryBuilder,
        CreatePaginator $annotation
    ) {
        foreach ($annotation->getOrderBy() as $orderBy) {
            if (is_array($orderBy)) {
                $field = $this
                    ->providerCollector
                    ->provide(trim($orderBy[1]));

                $direction = $this
                    ->providerCollector
                    ->provide(
                        trim($orderBy[2]),
                        isset($orderBy[3]) && is_array($orderBy[3])
                            ? $orderBy[3]
                            : []
                    );

                $queryBuilder->addOrderBy(
                    trim($orderBy[0]) . '.' . $field,
                    $direction
                );
            }
        }
    }
}
