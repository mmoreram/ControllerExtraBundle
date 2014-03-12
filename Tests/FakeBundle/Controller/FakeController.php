<?php

/**
 * This file is part of the Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since  2013
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
     *      class = "FakeBundle:FakeEntity",
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      class = "FakeBundle:FakeEntity",
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClass",
     *      factoryClass = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *      factoryMethod = "create",
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClassNoStatic",
     *      factoryClass = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *      factoryMethod = "create",
     *      factoryStatic = false,
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClassStatic",
     *      factoryClass = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *      factoryMethod = "createStatic",
     *      factoryStatic = true,
     *      persist = false
     * )
     */
    public function entityAction()
    {
        return new Response();
    }

    /**
     * Public method for functional Tests
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityName",
     *      class = "FakeBundle:FakeEntity",
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      class = "FakeBundle:FakeEntity",
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClass",
     *      factoryClass = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *      factoryMethod = "create",
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClassNoStatic",
     *      factoryClass = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *      factoryMethod = "create",
     *      factoryStatic = false,
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClassStatic",
     *      factoryClass = "Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory",
     *      factoryMethod = "createStatic",
     *      factoryStatic = true,
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClass",
     *      factoryClass = "controller_extra_bundle.factory.fake",
     *      factoryMethod = "create",
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClassNoStatic",
     *      factoryClass = "controller_extra_bundle.factory.fake",
     *      factoryMethod = "create",
     *      factoryStatic = false,
     *      persist = false
     * )
     * @\Mmoreram\ControllerExtraBundle\Annotation\Entity(
     *      name = "entityFactoryClassStatic",
     *      factoryClass = "controller_extra_bundle.factory.fake",
     *      factoryMethod = "createStatic",
     *      factoryStatic = true,
     *      persist = false
     * )
     */
    public function entityFunctionalAction()
    {
        return new Response();
    }

    /**
     * Public method
     *
     * @\Mmoreram\ControllerExtraBundle\Annotation\JsonResponse()
     */
    public function jsonResponseFunctionalAction()
    {
        return array(
            'index' => 'value'
        );
    }
}
