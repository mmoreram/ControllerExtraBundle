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

/**
 * Class PaginatorLeftJoinsEvaluator.
 */
class PaginatorLeftJoinsEvaluator implements PaginatorEvaluator
{
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
        foreach ($annotation->getLeftJoins() as $leftJoin) {
            $queryBuilder->leftJoin(
                trim($leftJoin[0]) . '.' . trim($leftJoin[1]),
                trim($leftJoin[2])
            );

            if (isset($leftJoin[3]) && $leftJoin[3]) {
                $queryBuilder->addSelect(trim($leftJoin[2]));
            }
        }
    }
}
