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
 * Class PaginatorEvaluatorCollection.
 */
class PaginatorEvaluatorCollector implements PaginatorEvaluator
{
    /**
     * @var array
     *
     * Paginator Evaluator collection
     */
    private $paginatorEvaluators = [];

    /**
     * Add paginator evaluator collection.
     *
     * @param PaginatorEvaluator $paginatorEvaluator
     */
    public function addPaginatorEvaluator(PaginatorEvaluator $paginatorEvaluator)
    {
        $this->paginatorEvaluators[] = $paginatorEvaluator;
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
        foreach ($this->paginatorEvaluators as $paginatorEvaluator) {
            $paginatorEvaluator->evaluate($queryBuilder, $annotation);
        }
    }
}
