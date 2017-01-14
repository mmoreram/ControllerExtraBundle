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

namespace Mmoreram\ControllerExtraBundle\Tests\Functional\Provider;

use Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake;
use Mmoreram\ControllerExtraBundle\Tests\Functional\FunctionalTest;

/**
 * Class EntityProviderTest.
 */
class EntityProviderTest extends FunctionalTest
{
    /**
     * Testing evaluateEntityNamespace.
     *
     * @param string $namespace
     *
     * @dataProvider dataEvaluateEntityNamespace
     */
    public function testEvaluateEntityNamespace(string $namespace)
    {
        $this->assertEquals(
            Fake::class,
            $this
                ->get('controller_extra.provider.entity')
                ->evaluateEntityNamespace($namespace)
        );
    }

    /**
     * Data for testEvaluateEntityNamespace.
     */
    public function dataEvaluateEntityNamespace() : array
    {
        return [
            ['Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake'],
            ['controller_extra.entity.fake.class'],
            ['FakeBundle:Fake'],
        ];
    }

    /**
     * Testing evaluateEntityInstanceFactory.
     *
     * @param array $factory
     *
     * @dataProvider dataEvaluateEntityInstanceFactory
     */
    public function testEvaluateEntityInstanceFactory(array $factory)
    {
        $this->assertInstanceOf(
            Fake::class,
            $this
                ->get('controller_extra.provider.entity')
                ->evaluateEntityInstanceFactory($factory)
        );
    }

    /**
     * Provider for testProvide.
     *
     * @return array
     */
    public function dataEvaluateEntityInstanceFactory() : array
    {
        return [
            [
                [
                    'class' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                    'static' => false,
                ],
            ],
            [
                [
                    'class' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                    'method' => 'generateNonStatic',
                    'static' => false,
                ],
            ],
            [
                [
                    'class' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                    'method' => 'generate',
                    'static' => true,
                ],
            ],
            [
                [
                    'class' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory',
                    'method' => 'generate',
                ],
            ],
            [
                [
                    'class' => 'controller_extra.factory.fake',
                    'static' => false,
                ],
            ],
            [
                [
                    'class' => 'controller_extra.factory.fake',
                    'method' => 'generateNonStatic',
                    'static' => false,
                ],
            ],
            [
                [
                    'class' => 'controller_extra.factory.fake',
                    'method' => 'generate',
                    'static' => true,
                ],
            ],
            [
                [
                    'class' => 'controller_extra.factory.fake',
                    'method' => 'generate',
                ],
            ],
        ];
    }
}
