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

namespace Mmoreram\ControllerExtraBundle\Tests\Functional\Resolver;

use Mmoreram\ControllerExtraBundle\Tests\Functional\FunctionalTest;

/**
 * Class JsonResponseAnnotationResolverTest.
 */
class JsonResponseAnnotationResolverTest extends FunctionalTest
{
    /**
     * Test annotation for a right request.
     */
    public function testAnnotationForRightRequest()
    {
        $this->client->request('GET', '/fake/jsonresponse');

        $this->assertEquals(
            '{"index":"value"}',
            $this
                ->client
                ->getResponse()
                ->getContent()
        );
    }

    /**
     * Test annotation for a request launching an exception.
     */
    public function testAnnotationRequestWithException()
    {
        $this->client->request('GET', '/fake/jsonresponseexception');

        $response = $this
            ->client
            ->getResponse();

        $this->assertEquals(
            'Exception message',
            json_decode($response->getContent(), true)['message']
        );

        $this->assertEquals(
            '500',
            $response->getStatusCode()
        );
    }

    /**
     * Test annotation for a request launching a http exception.
     */
    public function testAnnotationRequestWithHttpException()
    {
        $this->client->request('GET', '/fake/jsonresponsehttpexception');

        $response = $this
            ->client
            ->getResponse();

        $this->assertEquals(
            'Not found exception',
            json_decode($response->getContent(), true)['message']
        );

        $this->assertEquals(
            '404',
            $response->getStatusCode()
        );
    }
}
