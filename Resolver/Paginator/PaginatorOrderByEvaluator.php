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
use Mmoreram\ControllerExtraBundle\Provider\RequestParameterProvider;
use Mmoreram\ControllerExtraBundle\Resolver\Paginator\Interfaces\PaginatorEvaluatorInterface;

/**
 * Class PaginatorOrderByEvaluator
 */
class PaginatorOrderByEvaluator implements PaginatorEvaluatorInterface
{
    /**
     * @var RequestParameterProvider
     *
     * Request Parameter provider
     */
    protected $requestParameterProvider;

    /**
     * Construct
     *
     * @param RequestParameterProvider $requestParameterProvider Request Parameter provider
     */
    public function __construct(RequestParameterProvider $requestParameterProvider)
    {
        $this->requestParameterProvider = $requestParameterProvider;
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
        if (is_array($annotation->getOrderBy())) {

            foreach ($annotation->getOrderBy() as $orderBy) {

                if (is_array($orderBy)) {

                    $field = $this
                        ->requestParameterProvider
                        ->getParameterValue($orderBy[0]);

                    $direction = $this
                        ->requestParameterProvider
                        ->getParameterValue(
                            $orderBy[1],
                            isset($orderBy[2])
                                ? $orderBy[2]
                                : null
                        );

                    $queryBuilder->addOrderBy(
                        $field,
                        $direction
                    );
                }
            }
        }

        return $this;
    }

}
