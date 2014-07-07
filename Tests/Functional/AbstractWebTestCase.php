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

namespace Mmoreram\ControllerExtraBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class AbstractWebTestCase
 */
class AbstractWebTestCase extends WebTestCase
{
    /**
     * @var Client
     *
     * client
     */
    protected $client;

    /**
     * @var Application
     *
     * application
     */
    protected static $application;

    /**
     * Setup
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        static::$application = new Application(static::$kernel);
        static::$application->setAutoExit(false);
        $this->client = static::createClient();

        static::$application->run(new ArrayInput(array(
            'command'          => 'doctrine:database:drop',
            '--no-interaction' => true,
            '--force'          => true,
            '--quiet'          => true,
        )));

        static::$application->run(new ArrayInput(array(
            'command'          => 'doctrine:database:create',
            '--no-interaction' => true,
            '--quiet'          => true,
        )));

        static::$application->run(new ArrayInput(array(
            'command'          => 'doctrine:schema:create',
            '--no-interaction' => true,
            '--quiet'          => true,
        )));
    }
}
