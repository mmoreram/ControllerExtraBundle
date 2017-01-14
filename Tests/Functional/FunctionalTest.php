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

namespace Mmoreram\ControllerExtraBundle\Tests\Functional;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpKernel\KernelInterface;

use Mmoreram\BaseBundle\BaseBundle;
use Mmoreram\BaseBundle\Tests\BaseFunctionalTest;
use Mmoreram\BaseBundle\Tests\BaseKernel;
use Mmoreram\ControllerExtraBundle\ControllerExtraBundle;
use Mmoreram\ControllerExtraBundle\Tests\FakeBundle\FakeBundle;

/**
 * Class FunctionalTest.
 */
abstract class FunctionalTest extends BaseFunctionalTest
{
    /**
     * @var Client
     *
     * Client
     */
    protected $client;

    /**
     * Setup.
     */
    public function setUp()
    {
        parent::setUp();

        $this->client = self::createClient();
    }

    /**
     * Schema must be loaded in all test cases.
     *
     * @return bool
     */
    protected static function loadSchema() : bool
    {
        return true;
    }

    /**
     * Get kernel.
     *
     * @return KernelInterface
     */
    protected static function getKernel() : KernelInterface
    {
        return new BaseKernel(
            [
                FrameworkBundle::class,
                DoctrineBundle::class,
                MonologBundle::class,
                ControllerExtraBundle::class,
                FakeBundle::class,
                BaseBundle::class,
            ],
            [
                'imports' => [
                    ['resource' => '@BaseBundle/Resources/config/providers.yml'],
                    ['resource' => '@BaseBundle/Resources/test/framework.test.yml'],
                    ['resource' => '@BaseBundle/Resources/test/doctrine.test.yml'],
                ],
                'framework' => [
                    'form' => true,
                ],
                'monolog' => [
                    'handlers' => [
                        'main' => [
                            'type' => 'stream',
                            'level' => 'debug',
                            'handler' => null,
                        ],
                    ],
                ],
            ],
            [
                'fake_bundle' => '@FakeBundle/Resources/config/routing.yml',
            ]
        );
    }
}
