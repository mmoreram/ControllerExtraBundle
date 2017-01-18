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

namespace Mmoreram\ControllerExtraBundle\Resolver;

use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator as KnpPaginator;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;

use Mmoreram\ControllerExtraBundle\Annotation\Annotation;
use Mmoreram\ControllerExtraBundle\Annotation\CreatePaginator;
use Mmoreram\ControllerExtraBundle\Provider\EntityProvider;
use Mmoreram\ControllerExtraBundle\Provider\Provider;
use Mmoreram\ControllerExtraBundle\Resolver\Paginator\PaginatorEvaluatorCollector;
use Mmoreram\ControllerExtraBundle\ValueObject\PaginatorAttributes;

/**
 * Class PaginatorAnnotationResolver.
 */
final class PaginatorAnnotationResolver extends AnnotationResolver
{
    /**
     * @var AbstractManagerRegistry
     *
     * Doctrine
     */
    private $doctrine;

    /**
     * @var EntityProvider
     *
     * Entity provider
     */
    private $entityProvider;

    /**
     * @var Provider
     *
     * Provider collector
     */
    private $providerCollector;

    /**
     * @var PaginatorEvaluatorCollector
     *
     * PaginatorEvaluator collection
     */
    private $paginatorEvaluatorCollector;

    /**
     * @var string
     *
     * Default name
     */
    private $defaultName;

    /**
     * @var int
     *
     * Default page value
     */
    private $defaultPage;

    /**
     * @var int
     *
     * Default page value
     */
    private $defaultLimitPerPage;

    /**
     * Construct method.
     *
     * @param AbstractManagerRegistry     $doctrine
     * @param EntityProvider              $entityProvider
     * @param Provider                    $providerCollector
     * @param PaginatorEvaluatorCollector $paginatorEvaluatorCollector
     * @param string                      $defaultName
     * @param int                         $defaultPage
     * @param int                         $defaultLimitPerPage
     */
    public function __construct(
        AbstractManagerRegistry $doctrine,
        EntityProvider $entityProvider,
        Provider $providerCollector,
        PaginatorEvaluatorCollector $paginatorEvaluatorCollector,
        string $defaultName,
        int $defaultPage,
        int $defaultLimitPerPage
    ) {
        $this->doctrine = $doctrine;
        $this->entityProvider = $entityProvider;
        $this->providerCollector = $providerCollector;
        $this->paginatorEvaluatorCollector = $paginatorEvaluatorCollector;
        $this->defaultName = $defaultName;
        $this->defaultPage = $defaultPage;
        $this->defaultLimitPerPage = $defaultLimitPerPage;
    }

    /**
     * Specific annotation evaluation.
     *
     * All method code will executed only if specific active flag is true
     *
     * @param Request          $request
     * @param Annotation       $annotation
     * @param ReflectionMethod $method
     *
     * @return null|string
     */
    public function evaluateAnnotation(
        Request $request,
        Annotation $annotation,
        ReflectionMethod $method
    ) : ? string {
        /**
         * Annotation is only loaded if is type-of AnnotationEntity.
         */
        if ($annotation instanceof CreatePaginator) {

            /**
             * Creating new instance of desired entity.
             */
            $entity = $this
                ->entityProvider
                ->evaluateEntityNamespace($annotation->getEntityNamespace());

            /**
             * We create a basic query builder.
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

            $paginator = new DoctrinePaginator($queryBuilder, true);

            /**
             * Calculating limit of elements per page. Value can be evaluated
             * using as reference a Request attribute value.
             */
            $annotationLimit = $annotation->getLimit() ?? $this->defaultLimitPerPage;
            $limitPerPage = is_int($annotationLimit)
                ? $annotationLimit
                : (int) $this
                    ->providerCollector
                    ->provide($annotationLimit);

            /**
             * Calculating page to fetch. Value can be evaluated using as
             * reference a Request attribute value.
             */
            $annotationPage = $annotation->getPage() ?? $this->defaultPage;
            $page = is_int($annotationPage)
                ? $annotationPage
                : (int) $this
                    ->providerCollector
                    ->provide($annotationPage);

            /**
             * If attributes is not null, this bundle will place in the method
             * parameter named as defined a new PaginatorAttributes Value Object
             * with all needed data.
             */
            $this->evaluateAttributes(
                $request,
                $annotation,
                $paginator,
                $limitPerPage,
                $page
            );

            /**
             * Calculating offset, given number per pageand page.
             */
            $offset = $limitPerPage * ($page - 1);

            /**
             * Retrieving the Paginator iterator.
             */
            $paginator
                ->getQuery()
                ->setFirstResult($offset)
                ->setMaxResults($limitPerPage);

            /**
             * Get the parameter name. If not defined, is set as defined in
             * parameters.
             */
            $parameterName = $annotation->getName() ?: $this->defaultName;
            $parameterType = $this->getParameterType(
                $method,
                $parameterName
            );

            $dql = $paginator->getQuery()->getDQL();
            $paginator = $this
                ->decidePaginatorFormat(
                    $paginator,
                    $parameterType,
                    $limitPerPage,
                    $page
                );

            $request->attributes->set(
                $parameterName,
                $paginator
            );

            return $dql;
        }

        return null;
    }

    /**
     * Generate QueryBuilder.
     *
     * @param string $entityNamespace
     *
     * @return QueryBuilder
     */
    private function createQueryBuilder(string $entityNamespace) : QueryBuilder
    {
        return $this
            ->doctrine
            ->getManagerForClass($entityNamespace)
            ->createQueryBuilder()
            ->select(['x'])
            ->from($entityNamespace, 'x');
    }

    /**
     * Evaluates Paginator attributes.
     *
     * @param Request           $request
     * @param CreatePaginator   $annotation
     * @param DoctrinePaginator $paginator
     * @param int               $limitPerPage
     * @param int               $page
     */
    private function evaluateAttributes(
        Request $request,
        CreatePaginator $annotation,
        DoctrinePaginator $paginator,
        int $limitPerPage,
        int $page
    ) {
        if ($annotation->getAttributes()) {
            $total = $paginator->count();
            $paginatorAttributes = new PaginatorAttributes(
                (int) ceil($total / $limitPerPage),
                $total,
                $page,
                $limitPerPage
            );

            $request->attributes->set(
                trim($annotation->getAttributes()),
                $paginatorAttributes
            );
        }
    }

    /**
     * Return real usable Paginator instance given the definition type.
     *
     * @param DoctrinePaginator $paginator
     * @param string            $parameterType
     * @param int               $limitPerPage
     * @param int               $page
     *
     * @return mixed
     */
    private function decidePaginatorFormat(
        DoctrinePaginator $paginator,
        string $parameterType,
        int $limitPerPage,
        int $page
    ) {
        if (Pagerfanta::class === $parameterType) {
            $paginator = new Pagerfanta(new DoctrineORMAdapter($paginator->getQuery()));
            $paginator->setMaxPerPage($limitPerPage);
            $paginator->setCurrentPage($page);
        }
        if (PaginationInterface::class === $parameterType) {
            $knp = new KnpPaginator();
            $paginator = $knp->paginate($paginator->getQuery(), $page, $limitPerPage);
        }

        return $paginator;
    }
}
