<?php

/**
 * This file is part of the ControllerExtraBundle for Symfony2.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
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
use Mmoreram\ControllerExtraBundle\Provider\RequestParameterProvider;
use Mmoreram\ControllerExtraBundle\ValueObject\PaginatorAttributes;
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
     * @var RequestParameterProvider
     *
     * requestParameterProvider
     */
    protected $requestParameterProvider;

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
     * @param RequestParameterProvider    $requestParameterProvider    Request parameter provider
     * @param PaginatorEvaluatorCollector $paginatorEvaluatorCollector PaginatorEvaluator collector
     * @param string                      $defaultName                 Default name
     * @param integer                     $defaultPage                 Default page
     * @param integer                     $defaultLimitPerPage         Default limit per page
     */
    public function __construct(
        AbstractManagerRegistry $doctrine,
        EntityProvider $entityProvider,
        RequestParameterProvider $requestParameterProvider,
        PaginatorEvaluatorCollector $paginatorEvaluatorCollector,
        $defaultName,
        $defaultPage,
        $defaultLimitPerPage
    )
    {
        $this->doctrine = $doctrine;
        $this->entityProvider = $entityProvider;
        $this->requestParameterProvider = $requestParameterProvider;
        $this->paginatorEvaluatorCollector = $paginatorEvaluatorCollector;
        $this->defaultName = $defaultName;
        $this->defaultPage = $defaultPage;
        $this->defaultLimitPerPage = $defaultLimitPerPage;
    }

    /**
     * Specific annotation evaluation
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

            /**
             * We create a basic query builder
             */
            $queryBuilder = $this->createQueryBuilder($entity);

            /**
             * Every paginator evaluator is evaluated in this code.
             *
             * Every evaluated is defined using a Dependency Injection tag,
             * and accumulated in a Collector.
             * This collector evaluator, evaluates each one injected previously
             * by the DI Component
             */
            $this
                ->paginatorEvaluatorCollector
                ->evaluate($queryBuilder, $annotation);

            $paginator = new Paginator($queryBuilder, true);

            /**
             * Calculating limit of elements per page. Value can be evaluated
             * using as reference a Request attribute value
             */
            $limitPerPage = (int) $this
                ->requestParameterProvider
                ->getParameterValue(
                    $annotation->getLimit()
                    ? : $this->defaultLimitPerPage
                );

            /**
             * Calculating page to fetch. Value can be evaluated using as
             * reference a Request attribute value
             */
            $page = (int) $this
                ->requestParameterProvider
                ->getParameterValue(
                    $annotation->getPage()
                    ? : $this->defaultPage
                );

            /**
             * If attributes is not null, this bundle will place in the method
             * parameter named as defined a new PaginatorAttributes Value Object
             * with all needed data
             */
            $this->evaluateAttributes(
                $request,
                $annotation,
                $paginator,
                $limitPerPage,
                $page
            );

            /**
             * Calculating offset, given number per pageand page
             */
            $offset = $limitPerPage * ($page - 1);

            /**
             * Retrieving the Paginator iterator
             */
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

    /**
     * Evaluates Paginator attributes.
     *
     * @param Request             $request      Request
     * @param AnnotationPaginator $annotation   Annotation
     * @param Paginator           $paginator    Paginator
     * @param integer             $limitPerPage Limit per page
     * @param integer             $page         Page
     */
    protected function evaluateAttributes(
        Request $request,
        AnnotationPaginator $annotation,
        Paginator $paginator,
        $limitPerPage,
        $page
    )
    {
        if ($annotation->getAttributes()) {

            $paginatorAttributes = new PaginatorAttributes();
            $total = $paginator->count();

            $paginatorAttributes
                ->setCurrentPage($page)
                ->setTotalElements($total)
                ->setTotalPages(ceil($total / $limitPerPage));

            $request->attributes->set(
                trim($annotation->getAttributes()),
                $paginatorAttributes
            );
        }
    }
}
