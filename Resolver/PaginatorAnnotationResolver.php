<?php

/**
 * Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 */

namespace Mmoreram\ControllerExtraBundle\Resolver;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Paginator;

use Mmoreram\ControllerExtraBundle\Resolver\Abstracts\AbstractAnnotationResolver;
use Mmoreram\ControllerExtraBundle\Annotation\Paginator as AnnotationPaginator;
use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;


/**
 * PaginatorAnnotationResolver, an extension of AbstractAnnotationResolver
 */
class PaginatorAnnotationResolver extends AbstractAnnotationResolver
{

    /**
     * @var ObjectManager
     *
     * EntityManager
     */
    protected $entityManager;


    /**
     * @var Paginator
     *
     * Paginator
     */
    protected $paginator;


    /**
     * @var string
     *
     * Default number of elements per page
     */
    protected $numberDefault;


    /**
     * @var string
     *
     * Default page
     */
    protected $defaultPage;


    /**
     * @var string
     *
     * Default orderBy field
     */
    protected $defaultOrderByField;


    /**
     * @var string
     *
     * Default orderBy mode
     */
    protected $defaultOrderByMode;


    /**
     * Construct method
     *
     * @param ObjectManager $entityManager Entity Manager
     * @param Paginator     $paginator     Paginator
     */
    public function __construct(ObjectManager $entityManager, Paginator $paginator = null)
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }


    /**
     * Set default number of elements per page
     *
     * @param string $defaultNumber Default number of elements per page
     *
     * @return PaginatorAnnotationEventListener
     */
    public function setDefaultNumber($defaultNumber)
    {
        $this->defaultNumber = $defaultNumber;

        return $this;
    }


    /**
     * Set default page
     *
     * @param string $defaultPage Default page
     *
     * @return PaginatorAnnotationEventListener
     */
    public function setDefaultPage($defaultPage)
    {
        $this->defaultPage = $defaultPage;

        return $this;
    }


    /**
     * Set default orderBy field
     *
     * @param string $defaultOrderByField Default orderby field
     *
     * @return PaginationAnnotationEventListener self Object
     */
    public function setDefaultOrderByField($defaultOrderByField)
    {
        $this->defaultOrderByField = $defaultOrderByField;

        return $this;
    }


    /**
     * Set default orderBy mode
     *
     * @param string $defaultOrderByMode Default orderby mode
     *
     * @return PaginationAnnotationEventListener self Object
     */
    public function setDefaultOrderByMode($defaultOrderByMode)
    {
        $this->defaultOrderByMode = $defaultOrderByMode;

        return $this;
    }


    /**
     * Specific annotation evaluation.
     *
     * @param array      $controller        Controller
     * @param Request    $request           Request
     * @param Annotation $annotation        Annotation
     * @param array      $parametersIndexed Parameters indexed
     *
     * @return AbstractEventListener self Object
     */
    public function evaluateAnnotation(array $controller, Request $request, Annotation $annotation, array $parametersIndexed)
    {

        /**
         * Annotation is only laoded if is typeof AnnotationFlush
         */
        if ($annotation instanceof AnnotationPaginator) {


        }
    }
}