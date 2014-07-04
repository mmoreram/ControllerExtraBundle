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
 * Class PaginatorLeftJoinsEvaluator
 */
class PaginatorLeftJoinsEvaluator implements PaginatorEvaluatorInterface
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
        if (is_array($annotation->getLeftJoins())) {

            foreach ($annotation->getLeftJoins() as $leftJoin) {

                $queryBuilder->leftJoin(
                    $leftJoin[0],
                    $leftJoin[1]
                );

                if (isset($leftJoin[2]) && $leftJoin[2]) {

                    $queryBuilder->addSelect($leftJoin[1]);
                }
            }
        }

        return $this;
    }
}
