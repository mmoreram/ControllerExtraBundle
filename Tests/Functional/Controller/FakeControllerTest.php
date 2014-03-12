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

namespace Mmoreram\ControllerExtraBundle\Tests\Functional\Controller;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Tests FlushAnnotationResolver class
 */
class FakeControllerTest extends WebTestCase
{
    /**
     * Test controller entityFunctionalMethod
     */
    public function testEntityFunctionalMethod()
    {
        AnnotationRegistry::registerFile(dirname(__FILE__) . '/../../../Annotation/Entity.php');

        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $client = static::createClient();

        $client->request('GET', '/fake/entity');
    }

    /**
     * Test controller entityFunctionalMethod
     */
    public function testJsonResponseFunctionalMethod()
    {
        AnnotationRegistry::registerFile(dirname(__FILE__) . '/../../../Annotation/JsonResponse.php');

        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $client = static::createClient();

        /**
         * @var Crawler $crawler
         */
        $client->request('GET', '/fake/jsonresponse');
        $this->assertEquals('{"index":"value"}', $client->getResponse()->getContent());
    }
}
