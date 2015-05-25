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

namespace Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake;
use Mmoreram\ControllerExtraBundle\ValueObject\PaginatorAttributes;

/**
 * Fake Controller object
 */
class FakeController extends Controller
{
    /**
     * Public method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityName",
     *      class = "FakeBundle:Fake",
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      class = "FakeBundle:Fake",
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClass",
     *      class = {
     *          "factory" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "create",
     *      },
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClassNoStatic",
     *      class = {
     *          "factory" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "createNonStatic",
     *          "static" = false,
     *      },
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClassStatic",
     *      class = {
     *          "factory" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "create",
     *          "static" = true,
     *      },
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClass",
     *      class = {
     *          "factory" = "controller_extra_bundle.factory.fake",
     *          "method" = "create",
     *      },
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClassNoStatic",
     *      class = {
     *          "factory" = "controller_extra_bundle.factory.fake",
     *          "method" = "createNonStatic",
     *          "static" = false,
     *      },
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClassStatic",
     *      class = {
     *          "factory" = "controller_extra_bundle.factory.fake",
     *          "method" = "create",
     *          "static" = true,
     *      },
     *      persist = false
     * )
     */
    public function entityAction(Fake $entity)
    {
        return new Response();
    }

    /**
     * Public method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entity",
     *      class = "FakeBundle:Fake",
     *      mapping = {
     *          "id" = "~id~"
     *      }
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function entityMappedAction(Fake $entity)
    {
        return array(
            'id' => $entity->getId()
        );
    }

    /**
     * Public method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entity",
     *      class = "FakeBundle:Fake",
     *      mapping = {
     *          "id" = "~id~",
     *          "field" = "value",
     *      },
     *      mappingFallback = false
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFallback",
     *      class = "FakeBundle:Fake",
     *      mapping = {
     *          "id" = "~id~",
     *          "field" = "value",
     *      },
     *      mappingFallback = true
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function entityMappedManyAction(Fake $entity, Fake $entityFallback)
    {
        return array(
            'id' => $entity->getId(),
            'null' => $entityFallback->getId(),
        );
    }

    /**
     * Public method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entity",
     *      class = "FakeBundle:Fake",
     *      mapping = {
     *          "id" = "~id~",
     *          "field" = "value",
     *      },
     *      notFoundException = {
     *          "exception" = "Symfony\Component\HttpKernel\Exception\NotFoundHttpException",
     *          "message" = "Entity was not found"
     *      }
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function entityNotFoundExceptionAction(Fake $entity)
    {
    }

    /**
     * Public method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function jsonResponseAction()
    {
        return array(
            'index' => 'value'
        );
    }

    /**
     * Public method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function jsonResponseExceptionAction()
    {
        return new \Exception('Exception message');
    }

    /**
     * Public method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function jsonResponseHttpExceptionAction()
    {
        return new NotFoundHttpException('Not found exception');
    }

    /**
     * Public method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entity",
     *      class = "FakeBundle:Fake",
     *      mapping = {
     *          "id" = "~id~",
     *          "field" = "value",
     *      },
     *      notFoundException = {
     *          "exception" = "Symfony\Component\HttpKernel\Exception\NotFoundHttpException",
     *          "message" = "Exception launched from an annotation"
     *      }
     * )
     */
    public function jsonResponseAnnotationExceptionAction(Fake $entity)
    {
    }

    /**
     * Public pagination method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Paginator(
     *      class = {
     *          "factory" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "createNonStatic",
     *          "static" = false
     *      },
     *      page = 1,
     *      limit = 10,
     *      orderBy = {
     *          { "x", "createdAt", "ASC" },
     *          { "x", "id", "ASC" }
     *      },
     *      wheres = {
     *          { "x", "enabled" , "=", true }
     *      },
     *      leftJoins = {
     *          { "x", "relation", "r" },
     *          { "x", "relation2", "r2" },
     *          { "x", "relation5", "r5", true },
     *      },
     *      innerJoins = {
     *          { "x", "relation3", "r3" },
     *          { "x", "relation4", "r4", true },
     *      },
     *      notNulls = {
     *          {"x", "address1"},
     *          {"x", "address2"},
     *      }
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function paginatorAction(Paginator $paginator)
    {
        $dql = $paginator
            ->getQuery()
            ->getDQL();

        return array(
            'dql' => $dql,
        );
    }

    /**
     * Public pagination method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Paginator(
     *      class = {
     *          "factory" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "createNonStatic",
     *          "static" = false
     *      },
     *      page = "~page~",
     *      limit = "~limit~",
     *      orderBy = {
     *          { "x", "~field~", "~dir~", {
     *              "1" = "ASC",
     *              "2" = "DESC",
     *          }}
     *      },
     *      wheres = {
     *          { "x", "id" , ">=", 1 }
     *      },
     *      notNulls = {
     *          {"x", "id"},
     *          {"x", "field"},
     *      }
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function paginatorSimpleAction(Paginator $paginator)
    {
        return array(
            'count' => $paginator->getIterator()->count()
        );
    }

    /**
     * Public pagination method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Paginator(
     *      class = {
     *          "factory" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "createNonStatic",
     *          "static" = false
     *      },
     *      page = "~page~",
     *      limit = "~limit~",
     *      orderBy = {
     *          { "x", "~field~", "~dir~", {
     *              "1" = "ASC",
     *              "2" = "DESC",
     *          }}
     *      },
     *      wheres = {
     *          { "x", "id" , ">=", 1 },
     *          { "x", "field" , "=", "test" }
     *      },
     *      notNulls = {
     *          {"x", "id"},
     *          {"x", "field"},
     *      }
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function paginatorMultipleWhereAction(Paginator $paginator)
    {
        return array(
            'count' => $paginator->getIterator()->count()
        );
    }

    /**
     * Public pagination method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Paginator(
     *      class = {
     *          "factory" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "createNonStatic",
     *          "static" = false
     *      },
     *      page = "~page~",
     *      limit = "~limit~",
     *      orderBy = {
     *          { "x", "~field~", "~dir~", {
     *              "1" = "ASC",
     *              "2" = "DESC",
     *          }}
     *      },
     *      wheres = {
     *          { "x", "id" , ">=", 2 }
     *      },
     *      notNulls = {
     *          {"x", "id"},
     *          {"x", "field"},
     *      }
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function paginatorNotMatchingAction(Paginator $paginator)
    {
        return array(
            'count' => $paginator->getIterator()->count()
        );
    }

    /**
     * Public pagination method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Paginator(
     *      attributes = "paginatorAttributes",
     *      class = {
     *          "factory" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "createNonStatic",
     *          "static" = false
     *      },
     *      page = "~page~",
     *      limit = "~limit~",
     *      orderBy = {
     *          { "x", "~field~", "~dir~", {
     *              "1" = "ASC",
     *              "2" = "DESC",
     *          }}
     *      },
     *      wheres = {
     *          { "x", "id" , ">=", 2 }
     *      },
     *      notNulls = {
     *          {"x", "id"},
     *          {"x", "field"},
     *      }
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function paginatorAttributesAction(
        Paginator $paginator,
        PaginatorAttributes $paginatorAttributes
    )
    {
        return array(
            'count' => $paginator->getIterator()->count(),
            'totalPages' => $paginatorAttributes->getTotalPages(),
            'totalElements' => $paginatorAttributes->getTotalElements(),
            'currentPage' => $paginatorAttributes->getCurrentPage(),
        );
    }

    /**
     * Public pagination method with pagerfanta instance
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Paginator(
     *      class = {
     *          "factory" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "createNonStatic",
     *          "static" = false
     *      },
     *      page = "~page~",
     *      limit = "~limit~",
     *      orderBy = {
     *          { "x", "~field~", "~dir~", {
     *              "1" = "ASC",
     *              "2" = "DESC",
     *          }}
     *      }
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function paginatorPagerFantaAction(
        Pagerfanta $paginator
    )
    {
        return array(
            'count' => $paginator->getIterator()->count(),
        );
    }

    /**
     * Public objectManager method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\ObjectManager(
     *      name = "objectManager1",
     *      class = {
     *          "factory" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "createNonStatic",
     *          "static" = false
     *      }
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\ObjectManager(
     *      name = "objectManager2",
     *      class = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake"
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\ObjectManager(
     *      name = "objectManager3",
     *      class = "FakeBundle:Fake"
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function objectManagerAction(
        ObjectManager $objectManager1,
        ObjectManager $objectManager2,
        ObjectManager $objectManager3
    )
    {
        return array();
    }

    /**
     * Tested that works mapping fallback. Mapping fallback disabled and failing
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entity",
     *      class = "FakeBundle:Fake",
     *      mapping = {
     *          "id" = "~non-existing~",
     *      },
     *      mappingFallback = true
     * )
     */
    public function entityMappingFallbackAction(Fake $entity)
    {
        return new Response();
    }

    /**
     * Public pagination method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Paginator(
     *      attributes = "paginatorAttributes",
     *      class = {
     *          "factory" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "createNonStatic",
     *          "static" = false
     *      },
     *      limit = "?limit?",
     *      page = "?page?",
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function paginatorQueryAction(
        Paginator $paginator,
        PaginatorAttributes $paginatorAttributes
    )
    {
        return array(
            'count' => $paginator->getIterator()->count(),
            'totalPages' => $paginatorAttributes->getTotalPages(),
            'totalElements' => $paginatorAttributes->getTotalElements(),
            'currentPage' => $paginatorAttributes->getCurrentPage(),
        );
    }

    /**
     * Public pagination method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Paginator(
     *      attributes = "paginatorAttributes",
     *      class = {
     *          "factory" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "createNonStatic",
     *          "static" = false
     *      },
     *      limit = "#limit#",
     *      page = "#page#",
     *      wheres = {
     *          { "x", "id" , "LIKE", "#id#", true }
     *      },
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function paginatorRequestAction(
        Paginator $paginator,
        PaginatorAttributes $paginatorAttributes
    )
    {
        return array(
            'count' => $paginator->getIterator()->count(),
            'totalPages' => $paginatorAttributes->getTotalPages(),
            'totalElements' => $paginatorAttributes->getTotalElements(),
            'currentPage' => $paginatorAttributes->getCurrentPage(),
        );
    }

    /**
     * Gets a query string parameter without changing the param name
     *
     * @param string $param The query string param
     *
     * @return array The retrieved param
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Get(
     *      path = "param"
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function queryStringAction(
        $param
    ) {
        return array(
            'param' => $param
        );
    }

    /**
     * Gets a query string parameter changing the param name
     *
     * @param string $getParam The query string param
     *
     * @return array The retrieved param
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Get(
     *      path = "param",
     *      name = "getParam"
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function queryStringChangingNameAction(
        $getParam
    ) {
        return array(
            'param' => $getParam
        );
    }

    /**
     * Gets a query string parameter changing default value
     *
     * @param string $param The query string param
     *
     * @return array The retrieved param
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Get(
     *      path = "param",
     *      default = "default-value"
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function queryStringChangingDefaultValueAction(
        $param
    ) {
        return array(
            'param' => $param
        );
    }

    /**
     * Gets a query string using deep option
     *
     * @param string $param The query string param
     *
     * @return array The retrieved param
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Get(
     *      path = "param[key]",
     *      name = "param",
     *      deep = true
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function queryStringDeepAction(
        $param
    ) {
        return array(
            'param' => $param
        );
    }

    /**
     * Gets a post parameter without changing the param name
     *
     * @param string $param The post param
     *
     * @return array The retrieved param
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Post(
     *      path = "param"
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function postParameterAction(
        $param
    ) {
        return array(
            'param' => $param
        );
    }

    /**
     * Gets a post parameter changing the param name
     *
     * @param string $getParam The post param
     *
     * @return array The retrieved param
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Post(
     *      path = "param",
     *      name = "getParam"
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function postParameterChangingNameAction(
        $getParam
    ) {
        return array(
            'param' => $getParam
        );
    }

    /**
     * Gets a post parameter changing default value
     *
     * @param string $param The post param
     *
     * @return array The retrieved param
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Post(
     *      path = "param",
     *      default = "default-value"
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function postParameterChangingDefaultValueAction(
        $param
    ) {
        return array(
            'param' => $param
        );
    }

    /**
     * Gets a post using deep option
     *
     * @param string $param The post param
     *
     * @return array The retrieved param
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Post(
     *      path = "param[key]",
     *      name = "param",
     *      deep = true
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function postParameterDeepAction(
        $param
    ) {
        return array(
            'param' => $param
        );
    }

    /**
     * Form methods
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Form(
     *      class = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Form\Type\FakeType",
     *      name = "form1"
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Form(
     *      class = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Form\Type\FakeType",
     *      name = "form2"
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Form(
     *      class = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Form\Type\FakeType",
     *      name = "form3"
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Form(
     *      class = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Form\Type\FakeType",
     *      name = "form4"
     * )
     */
    public function formAction(
        AbstractType $form1,
        FormInterface $form2,
        FormView $form3
    )
    {
        return new Response();
    }
}
