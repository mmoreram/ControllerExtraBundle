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
 * Class PaginatorWheresEvaluator.
 */
class PaginatorWheresEvaluator implements PaginatorEvaluator
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
        $iteration = 0;

        foreach ($annotation->getWheres() as $where) {
            $annotationWhereParameter = $where[3];
            $processedWhereValue = $annotationWhereParameter;

            /**
             * If string, we can search for references in query parameters.
             */
            if (is_string($annotationWhereParameter)) {
                $whereParameter = $this->clearWildcards($annotationWhereParameter);

                $whereValue = $this
                    ->providerCollector
                    ->provide($whereParameter);

                $whereValue = $this->addWildcards($annotationWhereParameter, $whereValue);
                $optionalFilter = isset($where[4])
                    ? (bool) $where[4]
                    : false;

                if ($optionalFilter && ($whereParameter === $whereValue)) {
                    continue;
                }

                $processedWhereValue = $whereValue;
            }

            $queryBuilder
                ->andWhere(trim($where[0]) . '.' . trim($where[1]) . ' ' . $where[2] . ' ?0' . $iteration)
                ->setParameter('0' . $iteration, $processedWhereValue);

            ++$iteration;
        }
    }

    /**
     * Remove wildcards from query if necessary.
     *
     * @param string $whereValue
     *
     * @return string
     */
    private function clearWildcards(string $whereValue) : string
    {
        return trim($whereValue, '%');
    }

    /**
     * Add wildcards to query if necessary.
     *
     * @param string $annotationWhereParameter
     * @param string $whereValue
     *
     * @return string
     */
    private function addWildcards(
        string $annotationWhereParameter,
        string $whereValue
    ) : string {
        if (substr($annotationWhereParameter, 0, 1) === '%') {
            $whereValue = '%' . $whereValue;
        }

        if (substr($annotationWhereParameter, -1, 1) === '%') {
            $whereValue = $whereValue . '%';
        }

        return $whereValue;
    }
}
