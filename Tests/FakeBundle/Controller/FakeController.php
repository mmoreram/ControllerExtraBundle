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

namespace Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Mmoreram\ControllerExtraBundle\Annotation\CreateForm;
use Mmoreram\ControllerExtraBundle\Annotation\CreatePaginator;
use Mmoreram\ControllerExtraBundle\Annotation\LoadEntity;
use Mmoreram\ControllerExtraBundle\Annotation\ToJsonResponse;
use Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake;
use Mmoreram\ControllerExtraBundle\ValueObject\PaginatorAttributes;

/**
 * Fake Controller object.
 */
class FakeController extends Controller
{
    /**
     * Public method.
     *
     * @LoadEntity(
     *      name = "entityName",
     *      namespace = "FakeBundle:Fake",
     *      persist = false
     * )
     * @LoadEntity(
     *      namespace = "FakeBundle:Fake",
     *      persist = false
     * )
     * @LoadEntity(
     *      name = "entityFactoryClass",
     *      namespace = "FakeBundle:Fake",
     *      factory = {
     *          "class" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "create",
     *      },
     *      persist = false
     * )
     * @LoadEntity(
     *      name = "entityFactoryClassNoStatic",
     *      namespace = "FakeBundle:Fake",
     *      factory = {
     *          "class" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "createNonStatic",
     *          "static" = false,
     *      },
     *      persist = false
     * )
     * @LoadEntity(
     *      name = "entityFactoryClassStatic",
     *      namespace = "FakeBundle:Fake",
     *      factory = {
     *          "class" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "create",
     *          "static" = true,
     *      },
     *      persist = false
     * )
     * @LoadEntity(
     *      name = "entityFactoryClass2",
     *      namespace = "FakeBundle:Fake",
     *      factory = {
     *          "class" = "controller_extra.factory.fake",
     *          "method" = "create",
     *      },
     *      persist = true
     * )
     * @LoadEntity(
     *      name = "entityFactoryClassNoStatic2",
     *      namespace = "FakeBundle:Fake",
     *      factory = {
     *          "class" = "controller_extra.factory.fake",
     *          "method" = "createNonStatic",
     *          "static" = false,
     *      },
     *      persist = true
     * )
     * @LoadEntity(
     *      name = "entityFactoryClassStatic2",
     *      namespace = "FakeBundle:Fake",
     *      factory = {
     *          "class" = "controller_extra.factory.fake",
     *          "method" = "create",
     *          "static" = true,
     *      },
     *      persist = true
     * )
     * @ToJsonResponse()
     *
     * @param Fake $entity
     *
     * @return array
     */
    public function entityAction(
        Fake $entityName,
        Fake $entity,
        Fake $entityFactoryClass,
        Fake $entityFactoryClassNoStatic,
        Fake $entityFactoryClassStatic,
        Fake $entityFactoryClass2,
        Fake $entityFactoryClassNoStatic2,
        Fake $entityFactoryClassStatic2
    ) : array {
        $this->flush();

        return [
            is_null($entityName->getId()) &&
            is_null($entityName->getField()) &&
            is_null($entity->getId()) &&
            is_null($entity->getField()) &&
            is_null($entityFactoryClass->getId()) &&
            $entityFactoryClass->getField() === 's_c' &&
            is_null($entityFactoryClassNoStatic->getId()) &&
            $entityFactoryClassNoStatic->getField() === 'ns_c' &&
            is_null($entityFactoryClassStatic->getId()) &&
            $entityFactoryClassStatic->getField() === 's_c' &&
            !is_null($entityFactoryClass2->getId()) &&
            $entityFactoryClass2->getField() === 's_c' &&
            !is_null($entityFactoryClassNoStatic2->getId()) &&
            $entityFactoryClassNoStatic2->getField() === 'ns_c' &&
            !is_null($entityFactoryClassStatic2->getId()) &&
            $entityFactoryClassStatic2->getField() === 's_c',
        ];
    }

    /**
     * Public method.
     *
     * @LoadEntity(
     *      name = "entity",
     *      namespace = "FakeBundle:Fake",
     *      mapping = {
     *          "id" = "~id~"
     *      }
     * )
     *
     * @ToJsonResponse()
     *
     * @param Fake $entity
     *
     * @return array
     */
    public function entityMappedAction(Fake $entity) : array
    {
        return [
            'id' => $entity->getId(),
        ];
    }

    /**
     * Public method.
     *
     * @LoadEntity(
     *      name = "entity",
     *      namespace = "FakeBundle:Fake",
     *      mapping = {
     *          "id" = "~id~",
     *          "field" = "value",
     *      },
     *      mappingFallback = false
     * )
     * @LoadEntity(
     *      name = "entityFallback",
     *      namespace = "FakeBundle:Fake",
     *      mapping = {
     *          "id" = "~id~",
     *          "field" = "value",
     *      },
     *      mappingFallback = true
     * )
     * @LoadEntity(
     *      name = "entityRepo1",
     *      namespace = "FakeBundle:Fake",
     *      mapping = {
     *          "id" = "999"
     *      },
     *      repository = {
     *          "class" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Repository\AlternativeRepository",
     *          "method" = "findMeOnePlease"
     *      }
     * )
     * @LoadEntity(
     *      name = "entityRepo2",
     *      namespace = "FakeBundle:Fake",
     *      mapping = {
     *          "id" = "999"
     *      },
     *      repository = {
     *          "class" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Repository\AlternativeRepository"
     *      }
     * )
     * @LoadEntity(
     *      name = "entityRepo3",
     *      namespace = "FakeBundle:Fake",
     *      mapping = {
     *          "id" = "999"
     *      },
     *      repository = {
     *          "class" = "controller_extra.alternative_repository",
     *          "method" = "findMeOnePlease"
     *      }
     * )
     *
     * @ToJsonResponse()
     */
    public function entityMappedManyAction(
        Fake $entity,
        Fake $entityFallback,
        Fake $entityRepo1,
        Fake $entityRepo2,
        Fake $entityRepo3
    ) {
        $other =
            is_null($entityFallback->getId()) &&
            $entity->getField() !== 'alt-fob-' . $entity->getId() &&
            $entityRepo1->getField() === 'alt-999' &&
            $entityRepo2->getField() === 'alt-fob-999' &&
            $entityRepo3->getField() === 'alt-999';

        return [
            'id' => $entity->getId(),
            'other' => $other,
        ];
    }

    /**
     * Public method.
     *
     * @LoadEntity(
     *      name = "entity",
     *      namespace = "FakeBundle:Fake",
     *      mapping = {
     *          "id" = "~id~",
     *          "field" = "dflojslk",
     *      }
     * )
     *
     * @ToJsonResponse()
     */
    public function entityNotFoundExceptionAction(Fake $entity)
    {
    }

    /**
     * Public method.
     *
     * @ToJsonResponse()
     */
    public function jsonResponseAction()
    {
        return [
            'index' => 'value',
        ];
    }

    /**
     * Public method.
     *
     * @ToJsonResponse()
     */
    public function jsonResponseExceptionAction()
    {
        return new \Exception('Exception message');
    }

    /**
     * Public method.
     *
     * @ToJsonResponse()
     */
    public function jsonResponseHttpExceptionAction()
    {
        return new NotFoundHttpException('Not found exception');
    }

    /**
     * Public pagination method.
     *
     * @CreatePaginator(
     *      entityNamespace = "FakeBundle:Fake",
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
     * @ToJsonResponse()
     */
    public function paginatorAction(Paginator $paginator)
    {
        $dql = $paginator
            ->getQuery()
            ->getDQL();

        return [
            'dql' => $dql,
        ];
    }

    /**
     * Public pagination method.
     *
     * @CreatePaginator(
     *      entityNamespace = "FakeBundle:Fake",
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
     * @ToJsonResponse()
     */
    public function paginatorSimpleAction(Paginator $paginator)
    {
        return [
            'count' => $paginator->getIterator()->count(),
        ];
    }

    /**
     * Public pagination method.
     *
     * @CreatePaginator(
     *      entityNamespace = "FakeBundle:Fake",
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
     * @ToJsonResponse()
     */
    public function paginatorMultipleWhereAction(Paginator $paginator)
    {
        return [
            'count' => $paginator->getIterator()->count(),
        ];
    }

    /**
     * Public pagination method.
     *
     * @CreatePaginator(
     *      entityNamespace = "FakeBundle:Fake",
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
     * @ToJsonResponse()
     */
    public function paginatorNotMatchingAction(Paginator $paginator)
    {
        return [
            'count' => $paginator->getIterator()->count(),
        ];
    }

    /**
     * Public pagination method.
     *
     * @CreatePaginator(
     *      name = "paginator",
     *      entityNamespace = "FakeBundle:Fake",
     *      wheres = {
     *          { "x", "field" , "LIKE", "%?search?%" }
     *      }
     * )
     *
     * @CreatePaginator(
     *      name = "paginatorPartial1",
     *      entityNamespace = "FakeBundle:Fake",
     *      wheres = {
     *          { "x", "field" , "LIKE", "?search1?%" }
     *      }
     * )
     *
     * @CreatePaginator(
     *      name = "paginatorPartial2",
     *      entityNamespace = "FakeBundle:Fake",
     *      wheres = {
     *          { "x", "field" , "LIKE", "%?search2?" }
     *      }
     * )
     *
     * @ToJsonResponse()
     */
    public function PaginatorLikeWithGetParameterAction(
        Paginator $paginator,
        Paginator $paginatorPartial1,
        Paginator $paginatorPartial2
    ) {
        return [
            'count' => $paginator->getIterator()->count(),
            'count1' => $paginatorPartial1->getIterator()->count(),
            'count2' => $paginatorPartial2->getIterator()->count(),
        ];
    }

    /**
     * Public pagination method.
     *
     * @CreatePaginator(
     *      attributes = "paginatorAttributes",
     *      entityNamespace = "FakeBundle:Fake",
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
     * @ToJsonResponse()
     */
    public function paginatorAttributesAction(
        Paginator $paginator,
        PaginatorAttributes $paginatorAttributes
    ) {
        return [
            'count' => $paginator->getIterator()->count(),
            'totalPages' => $paginatorAttributes->getTotalPages(),
            'totalElements' => $paginatorAttributes->getTotalElements(),
            'currentPage' => $paginatorAttributes->getCurrentPage(),
        ];
    }

    /**
     * Public pagination method with pagerfanta instance.
     *
     * @CreatePaginator(
     *      entityNamespace = "FakeBundle:Fake",
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
     * @ToJsonResponse()
     */
    public function paginatorPagerFantaAction(
        Pagerfanta $paginator
    ) {
        return [
            'count' => $paginator->getIterator()->count(),
        ];
    }

    /**
     * Public pagination method with knppaginator instance.
     *
     * @CreatePaginator(
     *      entityNamespace = "FakeBundle:Fake",
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
     * @ToJsonResponse()
     */
    public function paginatorKNPPaginatorAction(
        PaginationInterface $paginator
    ) {
        return [
            'count' => $paginator->getTotalItemCount(),
        ];
    }

    /**
     * Tested that works mapping fallback. Mapping fallback disabled and failing.
     *
     * @LoadEntity(
     *      name = "entity",
     *      namespace = "FakeBundle:Fake",
     *      mapping = {
     *          "id" = "~non-existing~",
     *      },
     *      mappingFallback = true,
     *      persist = true
     * )
     *
     * @ToJsonResponse()
     */
    public function entityMappingFallbackAction(Fake $entity)
    {
        $this->flush();

        return [
            !is_null($entity->getId()),
        ];
    }

    /**
     * Public pagination method.
     *
     * @CreatePaginator(
     *      attributes = "paginatorAttributes",
     *      entityNamespace = "FakeBundle:Fake",
     *      limit = "?limit?",
     *      page = "?page?",
     * )
     *
     * @ToJsonResponse()
     */
    public function paginatorQueryAction(
        Paginator $paginator,
        PaginatorAttributes $paginatorAttributes
    ) {
        return [
            'count' => $paginator->getIterator()->count(),
            'totalPages' => $paginatorAttributes->getTotalPages(),
            'totalElements' => $paginatorAttributes->getTotalElements(),
            'currentPage' => $paginatorAttributes->getCurrentPage(),
        ];
    }

    /**
     * Public pagination method.
     *
     * @CreatePaginator(
     *      attributes = "paginatorAttributes",
     *      entityNamespace = "FakeBundle:Fake",
     *      limit = "#limit#",
     *      page = "#page#",
     *      wheres = {
     *          { "x", "id" , "LIKE", "#id#", true }
     *      },
     * )
     *
     * @ToJsonResponse()
     */
    public function paginatorRequestAction(
        Paginator $paginator,
        PaginatorAttributes $paginatorAttributes
    ) {
        return [
            'count' => $paginator->getIterator()->count(),
            'totalPages' => $paginatorAttributes->getTotalPages(),
            'totalElements' => $paginatorAttributes->getTotalElements(),
            'currentPage' => $paginatorAttributes->getCurrentPage(),
        ];
    }

    /**
     * Gets a query string parameter without changing the param name.
     *
     * @param string $param The query string param
     *
     * @return array The retrieved param
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Get(
     *      path = "param"
     * )
     *
     * @ToJsonResponse()
     */
    public function queryStringAction(
        $param
    ) {
        return [
            'param' => $param,
        ];
    }

    /**
     * Gets a query string parameter changing the param name.
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
     * @ToJsonResponse()
     */
    public function queryStringChangingNameAction(
        $getParam
    ) {
        return [
            'param' => $getParam,
        ];
    }

    /**
     * Gets a query string parameter changing default value.
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
     * @ToJsonResponse()
     */
    public function queryStringChangingDefaultValueAction(
        $param
    ) {
        return [
            'param' => $param,
        ];
    }

    /**
     * Gets a query string using deep option.
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
     * @ToJsonResponse()
     */
    public function queryStringDeepAction(
        $param
    ) {
        return [
            'param' => $param,
        ];
    }

    /**
     * Gets a post parameter without changing the param name.
     *
     * @param string $param The post param
     *
     * @return array The retrieved param
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Post(
     *      path = "param"
     * )
     *
     * @ToJsonResponse()
     */
    public function postParameterAction(
        $param
    ) {
        return [
            'param' => $param,
        ];
    }

    /**
     * Gets a post parameter changing the param name.
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
     * @ToJsonResponse()
     */
    public function postParameterChangingNameAction(
        $getParam
    ) {
        return [
            'param' => $getParam,
        ];
    }

    /**
     * Gets a post parameter changing default value.
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
     * @ToJsonResponse()
     */
    public function postParameterChangingDefaultValueAction(
        $param
    ) {
        return [
            'param' => $param,
        ];
    }

    /**
     * Form methods.
     *
     * @CreateForm(
     *      class = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Form\Type\FakeType",
     *      name = "form1"
     * )
     * @CreateForm(
     *      class = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Form\Type\FakeType",
     *      name = "form2",
     *      handleRequest = true,
     *      validate = "validate2"
     * )
     * @CreateForm(
     *      class = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Form\Type\FakeType",
     *      name = "form3",
     *      handleRequest = true,
     *      validate = "validate3"
     * )
     * @CreateForm(
     *      class = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Form\Type\FakeType",
     *      name = "form4",
     *      handleRequest = true,
     *      validate = "validate4"
     * )
     *
     * @ToJsonResponse()
     */
    public function formAction(
        AbstractType $form1,
        FormInterface $form2,
        FormView $form3,
        Form $form4,
        bool $validate2,
        bool $validate3,
        bool $validate4
    ) {
        return [true];
    }

    /**
     * Flush.
     */
    private function flush()
    {
        $this
            ->get('doctrine.orm.default_entity_manager')
            ->flush();
    }
}
