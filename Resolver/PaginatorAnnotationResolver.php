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

namespace Mmoreram\ControllerExtraBundle\Resolver;

use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\QueryBuilder;
use ReflectionMethod;

use Mmoreram\ControllerExtraBundle\Resolver\Interfaces\AnnotationResolverInterface;
use Mmoreram\ControllerExtraBundle\Resolver\Paginator\PaginatorEvaluatorCollector;
use Mmoreram\ControllerExtraBundle\Annotation\Paginator as AnnotationPaginator;
use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;
use Mmoreram\ControllerExtraBundle\Provider\EntityProvider;

/**
 * Class PaginatorAnnotationResolver
 */
class PaginatorAnnotationResolver implements AnnotationResolverInterface
{
    /**
     * @var AbstractManagerRegistry
     *
     * Doctrine
     */
    protected $doctrine;

    /**
     * @var EntityProvider
     *
     * Entity provider
     */
    protected $entityProvider;

    /**
     * @var PaginatorEvaluatorCollector
     *
     * PaginatorEvaluator collection
     */
    protected $paginatorEvaluatorCollector;

    /**
     * @var string
     *
     * Default name
     */
    protected $defaultName;

    /**
     * @var integer
     *
     * Default page value
     */
    protected $defaultPage;

    /**
     * @var integer
     *
     * Default page value
     */
    protected $defaultLimitPerPage;

    /**
     * Construct method
     *
     * @param AbstractManagerRegistry     $doctrine                    Doctrine
     * @param EntityProvider              $entityProvider              Entity Provider
     * @param PaginatorEvaluatorCollector $paginatorEvaluatorCollector PaginatorEvaluator collector
     * @param string                      $defaultName                 Default name
     * @param integer                     $defaultPage                 Default page
     * @param integer                     $defaultLimitPerPage         Default limit per page
     */
    public function __construct(
        AbstractManagerRegistry $doctrine,
        EntityProvider $entityProvider,
        PaginatorEvaluatorCollector $paginatorEvaluatorCollector,
        $defaultName,
        $defaultPage,
        $defaultLimitPerPage
    )
    {
        $this->doctrine = $doctrine;
        $this->entityProvider = $entityProvider;
        $this->paginatorEvaluatorCollector = $paginatorEvaluatorCollector;
        $this->defaultName = $defaultName;
        $this->defaultPage = $defaultPage;
        $this->defaultLimitPerPage = $defaultLimitPerPage;
    }

    /**
     * Specific annotation evaluation.
     *
     * This is an example of a complete Pagination definition
     *
     * @Mmoreram\Pagination(
     *      class = {
     *          "factory" = "Mmoreram\ControllerExtraBundle\Factory\EntityFactory",
     *          "method" = "create",
     *          "static" = false
     *      },
     *      page = "3",
     *      limit = "20",
     *      orderBy = {
     *          "createdAt" = "asc",
     *          "id"        = "asc",
     *      },
     *      wheres = {
     *          { "enabled =" , true }
     *      },
     *      leftJoins = {
     *          { "x.relation", "r" },
     *          { "x.relation2", "r2" },
     *      },
     *      innerJoins = {
     *          { "x.relation3", "r3" },
     *          { "x.relation4", "r4" },
     *      },
     *      notNulls = {
     *          "address1",
     *          "address2",
     *      },
     * )
     *
     * All method code will executed only if specific active flag is true
     *
     * @param Request          $request    Request
     * @param Annotation       $annotation Annotation
     * @param ReflectionMethod $method     Method
     *
     * @return AnnotationResolverInterface|string self Object or dql
     */
    public function evaluateAnnotation(
        Request $request,
        Annotation $annotation,
        ReflectionMethod $method
    )
    {
        /**
         * Annotation is only loaded if is type-of AnnotationEntity
         */
        if ($annotation instanceof AnnotationPaginator) {

            /**
             * Creating new instance of desired entity
             */
            $entity = $this
                ->entityProvider
                ->provide($annotation->getClass());

            $queryBuilder = $this->createQueryBuilder($entity);

            $this
                ->paginatorEvaluatorCollector
                ->evaluate($queryBuilder, $annotation);

            $paginator = new Paginator($queryBuilder, true);

            $limitPerPage = (int) $annotation->getLimit()
                ? : $this->defaultLimitPerPage;

            $page = (int) $annotation->getPage()
                ? : $this->defaultPage;

            $offset = $limitPerPage * ($page - 1);

            $paginator
                ->getQuery()
                ->setFirstResult($offset)
                ->setMaxResults($limitPerPage);

            /**
             * Get the parameter name. If not defined, is set as defined in
             * parameters
             */
            $parameterName = $annotation->getName()
                ? : $this->defaultName;

            $request->attributes->set(
                $parameterName,
                $paginator
            );

            return $paginator->getQuery()->getDQL();
        }

        return $this;
    }

    /**
     * Generate QueryBuilder
     *
     * @param Object $entity Entity instance
     *
     * @return QueryBuilder Query builder
     */
    public function createQueryBuilder($entity)
    {
        $entityNamespace = get_class($entity);

        return $this
            ->doctrine
            ->getManagerForClass(get_class($entity))
            ->createQueryBuilder()
            ->select(array('x'))
            ->from($entityNamespace, 'x');
    }
}
