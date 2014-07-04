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
use Symfony\Component\HttpKernel\Client;

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
     * Setup
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->client = static::createClient();
    }
}
