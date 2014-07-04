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
 * Class PaginatorInnerJoinsEvaluator
 */
class PaginatorInnerJoinsEvaluator implements PaginatorEvaluatorInterface
{

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
        if (is_array($annotation->getInnerJoins())) {

            foreach ($annotation->getInnerJoins() as $innerJoin) {

                $queryBuilder->innerJoin(
                    $innerJoin[0],
                    $innerJoin[1]
                );

                if (isset($innerJoin[2]) && $innerJoin[2]) {

                    $queryBuilder->addSelect($innerJoin[1]);
                }
            }
        }

        return $this;
    }
}
