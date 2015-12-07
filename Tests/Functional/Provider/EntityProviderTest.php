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

namespace Mmoreram\ControllerExtraBundle\Tests\Functional\Provider;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Mmoreram\ControllerExtraBundle\Provider\EntityProvider;

/**
 * Class EntityProviderTest.
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
     * Set up.
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
     * Testing class provider with good results.
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
     * Provider for testProvide.
     *
     * @return array
     */
    public function dataProvide()
    {
        return [
            [$this->entityNamespace],
            ['controller_extra_bundle.entity.fake.class'],
            ['FakeBundle:Fake'],
            [
                [
                    'factory' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                    'static' => false,
                ],
            ],
            [
                [
                    'factory' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                    'method' => 'generateNonStatic',
                    'static' => false,
                ],
            ],
            [
                [
                    'factory' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                    'method' => 'generate',
                    'static' => true,
                ],
            ],
            [
                [
                    'factory' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                    'method' => 'generate',
                ],
            ],
            [
                [
                    'factory' => 'controller_extra_bundle.factory.fake',
                    'static' => false,
                ],
            ],
            [
                [
                    'factory' => 'controller_extra_bundle.factory.fake',
                    'method' => 'generateNonStatic',
                    'static' => false,
                ],
            ],
            [
                [
                    'factory' => 'controller_extra_bundle.factory.fake',
                    'method' => 'generate',
                    'static' => true,
                ],
            ],
            [
                [
                    'factory' => 'controller_extra_bundle.factory.fake',
                    'method' => 'generate',
                ],
            ],
        ];
    }
}
