<?php

/**
 * This file is part of the Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 */

namespace Mmoreram\ControllerExtraBundle\Resolver\Paginator;

use Doctrine\ORM\QueryBuilder;
use Mmoreram\ControllerExtraBundle\Annotation\Paginator as AnnotationPaginator;
use Mmoreram\ControllerExtraBundle\Resolver\Paginator\Interfaces\PaginatorEvaluatorInterface;

/**
 * Class PaginatorEvaluatorCollection
 */
class PaginatorEvaluatorCollector implements PaginatorEvaluatorInterface
{
    /**
     * @var array
     *
     * Paginator Evaluator collection
     */
    protected $paginatorEvaluators = array();

    /**
     * Add paginator evaluator collection
     *
     * @param PaginatorEvaluatorInterface $paginatorEvaluator Paginator Evaluator
     *
     * @return PaginatorEvaluatorCollector self Object
     */
    public function addPaginatorEvaluator(PaginatorEvaluatorInterface $paginatorEvaluator)
    {
        $this->paginatorEvaluators[] = $paginatorEvaluator;

        return $this;
    }

    /**
     * Evaluates inner joins
     *
     * @param QueryBuilder        $queryBuilder Query builder
     * @param AnnotationPaginator $annotation   Annotation
     *
     * @return PaginatorEvaluatorInterface self Object
     */
    public function evaluate(
        QueryBuilder $queryBuilder,
        AnnotationPaginator $annotation
    )
    {
        /**
         * @var PaginatorEvaluatorInterface $paginatorEvaluator
         */
        foreach ($this->paginatorEvaluators as $paginatorEvaluator) {

            $paginatorEvaluator->evaluate($queryBuilder, $annotation);
        }

        return $this;
    }
}
