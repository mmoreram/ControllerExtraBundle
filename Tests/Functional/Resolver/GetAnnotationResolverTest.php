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

namespace Mmoreram\ControllerExtraBundle\Tests\Functional\Resolver;

use Mmoreram\ControllerExtraBundle\Tests\Functional\AbstractWebTestCase;

/**
 * Class GetAnnotationResolverTest.
 */
class GetAnnotationResolverTest extends AbstractWebTestCase
{
    /**
     * Test obtain a $_GET parameter.
     */
    public function testObtainGetParameterAnnotation()
    {
        $uri = '/fake/getquerystring?param=test';
        $this->client->request('GET', $uri);

        $response = json_decode($this
            ->client
            ->getResponse()
            ->getContent(), true);

        $this->assertEquals(
            'test',
            $response['param'],
            'The query string is not being correctly resolved'
        );
    }

    /**
     * Test obtain a non existent $_GET parameter.
     */
    public function testObtainNonExistentGetParameterAnnotation()
    {
        $uri = '/fake/getquerystring';
        $this->client->request('GET', $uri);

        $response = json_decode($this
            ->client
            ->getResponse()
            ->getContent(), true);

        $this->assertEquals(
            null,
            $response['param'],
            'The resolved param is supposed to be null if no param is received'
        );
    }

    /**
     * Test obtain a $_GET parameter changing the param name.
     */
    public function testObtainGetParameterChangingParamNameAnnotation()
    {
        $uri = '/fake/getquerystringchangingparamname?param=test';
        $this->client->request('GET', $uri);

        $response = json_decode($this
            ->client
            ->getResponse()
            ->getContent(), true);

        $this->assertEquals(
            'test',
            $response['param'],
            'The query string is not being correctly resolved'
        );
    }

    /**
     * Test obtain a $_GET parameter changing the param name.
     */
    public function testObtainNonExistentGetParameterChangingParamNameAnnotation()
    {
        $getMethod = new \ReflectionMethod('Symfony\Component\HttpFoundation\ParameterBag', 'get');
        if ($getMethod->getNumberOfParameters() === 2) {
            $this->markTestSkipped('Feature not allowed in Symfony ^3.0.0');
        }

        $uri = '/fake/getquerystringchangingparamname';
        $this->client->request('GET', $uri);

        $response = json_decode($this
            ->client
            ->getResponse()
            ->getContent(), true);

        $this->assertEquals(
            null,
            $response['param'],
            'The resolved param is supposed to be null if no param is received'
        );
    }

    /**
     * Test obtain a $_GET parameter changing the default value.
     */
    public function testObtainGetParameterChangingDefaultAnnotation()
    {
        $getMethod = new \ReflectionMethod('Symfony\Component\HttpFoundation\ParameterBag', 'get');
        if ($getMethod->getNumberOfParameters() === 2) {
            $this->markTestSkipped('Feature not allowed in Symfony ^3.0.0');
        }

        $uri = '/fake/getquerystringchangingdefaultvalue?param=value';
        $this->client->request('GET', $uri);

        $response = json_decode($this
            ->client
            ->getResponse()
            ->getContent(), true);

        $this->assertEquals(
            'value',
            $response['param'],
            'The query string is not being correctly resolved'
        );
    }

    /**
     * Test obtain a non existent $_GET parameter changing the default value.
     */
    public function testObtainNonExistentGetParameterChangingDefaultAnnotation()
    {
        $uri = '/fake/getquerystringchangingdefaultvalue';
        $this->client->request('GET', $uri);

        $response = json_decode($this
            ->client
            ->getResponse()
            ->getContent(), true);

        $this->assertEquals(
            'default-value',
            $response['param'],
            'The resolved param is supposed to be the default value if no param is received'
        );
    }
}
