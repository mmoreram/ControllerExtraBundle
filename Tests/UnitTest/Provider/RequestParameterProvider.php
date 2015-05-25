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

namespace Mmoreram\ControllerExtraBundle\Tests\UnitTest\Provider;

use PHPUnit_Framework_TestCase;

use Mmoreram\ControllerExtraBundle\Provider\RequestParameterProvider;

/**
 * Tests RequestParameterProvider class
 */
class RequestParameterProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test method that returns parameter from request
     *
     * @param $parameter
     * @param $result
     *
     * @dataProvider parametersProvider
     */
    public function testGetParameterValue($parameter, $result)
    {
        $parameterBagMock   = $this->getParameterBagMock();
        $requestMock        = $this->getRequestMock($parameterBagMock);
        $requestStackMock   = $this->getRequestStackMock($requestMock);
        $sut = new RequestParameterProvider($requestStackMock);
        $sut->setRequestType(RequestParameterProvider::CURRENT_REQUEST);
        $parameterValue = $sut->getParameterValue($parameter);
        $this->assertEquals($result, $parameterValue);
    }

    /**
     * Return a ParameterBag mock
     *
     * @return Symfony\Component\HttpFoundation\ParameterBag
     */
    private function getParameterBagMock()
    {
        $parameterBagMock = $this->getMock('Symfony\Component\HttpFoundation\ParameterBag');
        $parameterBagMock->method('has')
            ->willReturn(true);
        $parameterBagMock->method('get')
            ->willReturn('value');
        return $parameterBagMock;
    }

    /**
     * Return a Request mock
     *
     * @param $parameterBagMock
     *
     * @return Symfony\Component\HttpFoundation\Request
     */
    private function getRequestMock($parameterBagMock)
    {
        $requestMock = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $requestMock->attributes = $parameterBagMock;   // $_REQUEST
        $requestMock->request = $parameterBagMock;      // $_POST
        $requestMock->query = $parameterBagMock;        // $_GET
        return $requestMock;
    }

    /**
     * Return a RequestStack mock
     *
     * @param $requestMock
     *
     * @return Symfony\Component\HttpFoundation\RequestStack
     */
    private function getRequestStackMock($requestMock)
    {
        $requestStackMock = $this->getMock('Symfony\Component\HttpFoundation\RequestStack');
        $requestStackMock
            ->method('getCurrentRequest')
            ->willReturn($requestMock);
        return $requestStackMock;
    }
    
    /**
     * Data provider to provisioning parameterProvider with three parameter types (GET|POST|REQUEST)
     *
     * @return array
     */
    public function parametersProvider()
    {
        return array(
            array(
                '?test?',
                'value'
            ),
            array(
                '~test~',
                'value'
            ),
            array(
                '#test#',
                'value'
            )
        );
    }
}