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

namespace Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake;

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
     *          "method" = "create",
     *          "static" = false,
     *      },
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClassStatic",
     *      class = {
     *          "factory" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "createStatic",
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
     *          "method" = "create",
     *          "static" = false,
     *      },
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClassStatic",
     *      class = {
     *          "factory" = "controller_extra_bundle.factory.fake",
     *          "method" = "createStatic",
     *          "static" = true,
     *      },
     *      persist = false
     * )
     */
    public function entityAction()
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
     *      }
     * )
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function entityMappedManyAction(Fake $entity)
    {
        return array(
            'id' => $entity->getId()
        );
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
     * Public pagination method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Paginator(
     *      class = {
     *          "factory" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "create",
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
     *          "method" = "create",
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
            'count' => count($paginator)
        );
    }

    /**
     * Public pagination method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Paginator(
     *      class = {
     *          "factory" = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *          "method" = "create",
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
            'count' => count($paginator)
        );
    }
}
