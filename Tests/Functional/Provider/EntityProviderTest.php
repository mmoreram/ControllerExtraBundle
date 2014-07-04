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

namespace Mmoreram\ControllerExtraBundle\Tests\Functional\Provider;

use Mmoreram\ControllerExtraBundle\Provider\EntityProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class EntityProviderTest
 */
class EntityProviderTest extends WebTestCase
{
    /**
     * @var EntityProvider
     *
     * entity provider
     */
    protected $entityProvider;

    /**
     * @var string
     *
     * entity namespace
     */
    protected $entityNamespace = 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake';

    /**
     * Set up
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $this->entityProvider = static::$kernel
            ->getContainer()
            ->get('mmoreram.controllerextra.provider.entity_provider');
    }

    /**
     * Testing class provider with good results
     *
     * @dataProvider dataProvide
     */
    public function testProvide($entityDefinition)
    {
        $this->assertInstanceOf(
            $this->entityNamespace,
            $this->entityProvider->provide($entityDefinition)
        );
    }

    /**
     * Provider for testProvide
     *
     * @return array
     */
    public function dataProvide()
    {
        return array(
            array($this->entityNamespace),
            array('controller_extra_bundle.entity.fake.class'),
            array('FakeBundle:Fake'),
            array(
                array(
                    'factory' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                )
            ),
            array(
                array(
                    'factory' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                    'method' => 'generate',
                )
            ),
            array(
                array(
                    'factory' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                    'method' => 'create',
                )
            ),
            array(
                array(
                    'factory' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                    'static' => false,
                )
            ),
            array(
                array(
                    'factory' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                    'method' => 'generate',
                    'static' => false,
                )
            ),
            array(
                array(
                    'factory' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                    'method' => 'create',
                    'static' => false,
                )
            ),
            array(
                array(
                    'factory' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                    'static' => true,
                )
            ),
            array(
                array(
                    'factory' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                    'method' => 'generate',
                    'static' => true,
                )
            ),
            array(
                array(
                    'factory' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                    'method' => 'create',
                    'static' => true,
                )
            ),
            array(
                array(
                    'factory' => 'controller_extra_bundle.factory.fake',
                )
            ),
            array(
                array(
                    'factory' => 'controller_extra_bundle.factory.fake',
                    'method' => 'generate',
                )
            ),
            array(
                array(
                    'factory' => 'controller_extra_bundle.factory.fake',
                    'method' => 'create',
                )
            ),
            array(
                array(
                    'factory' => 'controller_extra_bundle.factory.fake',
                    'static' => false,
                )
            ),
            array(
                array(
                    'factory' => 'controller_extra_bundle.factory.fake',
                    'method' => 'generate',
                    'static' => false,
                )
            ),
            array(
                array(
                    'factory' => 'controller_extra_bundle.factory.fake',
                    'method' => 'create',
                    'static' => false,
                )
            ),
            array(
                array(
                    'factory' => 'controller_extra_bundle.factory.fake',
                    'static' => true,
                )
            ),
            array(
                array(
                    'factory' => 'controller_extra_bundle.factory.fake',
                    'method' => 'generate',
                    'static' => true,
                )
            ),
            array(
                array(
                    'factory' => 'controller_extra_bundle.factory.fake',
                    'method' => 'create',
                    'static' => true,
                )
            ),
        );
    }
}
