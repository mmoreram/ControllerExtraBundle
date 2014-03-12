<?php

/**
 * This file is part of the Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mmoreram\ControllerExtraBundle\Tests\Resolver;

/**
 * Tests JsonResponseAnnotationResolver class
 */
class JsonResponseAnnotationResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FlushAnnotationResolver
     *
     * Flush Annotation Resolver
     */
    private $flushAnnotationResolver;

    /**
     * @var Request
     *
     * Request
     */
    private $request;

    /**
     * @var ReflectionMethod
     *
     * Reflection Method
     */
    private $reflectionMethod;

    /**
     * @var Annotation
     *
     * Annotation
     */
    private $annotation;

    /**
     * Setup method
     */
    public function setUp()
    {
        $this->jsonResponseAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\JsonResponseAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getDefaultStatus',
                'getDefaultHeaders',
            ))
            ->getMock();

        $this->jsonResponseAnnotationResolver
            ->expects($this->any())
            ->method('getDefaultStatus')
            ->will($this->returnValue(200));

        $this->jsonResponseAnnotationResolver
            ->expects($this->any())
            ->method('getDefaultHeaders')
            ->will($this->returnValue(array()));

        $manager = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->reflectionMethod = $this
            ->getMockBuilder('ReflectionMethod')
            ->disableOriginalConstructor()
            ->getMock();

        $this->annotation = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Annotation\JsonResponse')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getStatus',
                'getHeaders',
            ))
            ->getMock();
    }

    /**
     * Tests DefaultManager name method
     *
     * @param integer $annotationStatus Status defined in annotation
     * @param integer $resultStatus     Result Status
     *
     * @dataProvider dataStatus
     */
    public function testStatus($annotationStatus, $resultStatus)
    {
        $annotation = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Annotation\JsonResponse')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getStatus',
            ))
            ->getMock();

        $annotation
            ->expects($this->any())
            ->method('getStatus')
            ->will($this->returnValue($annotationStatus));

        $this->jsonResponseAnnotationResolver->evaluateAnnotation($this->request, $annotation, $this->reflectionMethod);
        $this->assertEquals($resultStatus, $this->jsonResponseAnnotationResolver->getStatus());
    }

    /**
     * Data for testStatus
     *
     * @return array data
     */
    public function dataStatus()
    {
        return array(

            array(null, 200),
            array(false, 200),
            array('', 200),
            array(403, 403),
            array(200, 200)
        );
    }

    /**
     * Tests DefaultManager name method
     *
     * @param integer $annotationHeaders Headers defined in annotation
     * @param integer $resultHeaders     Result Headers
     *
     * @dataProvider dataHeaders
     */
    public function testHeaders($annotationHeaders, $resultHeaders)
    {
        $annotation = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Annotation\JsonResponse')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getHeaders',
            ))
            ->getMock();

        $annotation
            ->expects($this->any())
            ->method('getHeaders')
            ->will($this->returnValue($annotationHeaders));

        $this->jsonResponseAnnotationResolver->evaluateAnnotation($this->request, $annotation, $this->reflectionMethod);
        $this->assertEquals($resultHeaders, $this->jsonResponseAnnotationResolver->getHeaders());
    }

    /**
     * Data for testHeaders
     *
     * @return array data
     */
    public function dataHeaders()
    {
        return array(

            array(null, array()),
            array(false, array()),
            array('', array()),
            array(array(1, 'foo'), array(1, 'foo')),
            array(array(), array())
        );
    }

    /**
     * Tests Right Annotation
     */
    public function testAnnotationRight()
    {
        $this->jsonResponseAnnotationResolver->evaluateAnnotation(
            $this->request,
            $this->annotation,
            $this->reflectionMethod
        );

        $this->assertTrue($this->jsonResponseAnnotationResolver->getReturnJson());
    }

    /**
     * Tests Wrong Annotation
     */
    public function testAnnotationFail()
    {
        $annotation = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Annotation\Log')
            ->disableOriginalConstructor()
            ->getMock();

        $this->jsonResponseAnnotationResolver->evaluateAnnotation(
            $this->request,
            $annotation,
            $this->reflectionMethod
        );

        $this->assertFalse($this->jsonResponseAnnotationResolver->getReturnJson());
    }

    /**
     * Test onKernelView with right value
     */
    public function testOnKernelViewRight()
    {
        $jsonResponseAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\JsonResponseAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getReturnJson',
                'getStatus',
                'getHeaders',
            ))
            ->getMock();

        $jsonResponseAnnotationResolver
            ->expects($this->once())
            ->method('getReturnJson')
            ->will($this->returnValue(true));

        $jsonResponseAnnotationResolver
            ->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue(200));

        $jsonResponseAnnotationResolver
            ->expects($this->once())
            ->method('getHeaders')
            ->will($this->returnValue(array()));

        $event = $this
            ->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getControllerResult',
                'setResponse',
            ))
            ->getMock();

        $event
            ->expects($this->once())
            ->method('getControllerResult');

        $event
            ->expects($this->once())
            ->method('setResponse')
            ->with($this->isInstanceOf('Symfony\Component\HttpFoundation\JsonResponse'));

        $jsonResponseAnnotationResolver->onKernelView($event);
    }

    /**
     * Test onKernelResponse with wrong value
     */
    public function testOnKernelResponseWrong()
    {
        $jsonResponseAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\JsonResponseAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getReturnJson'
            ))
            ->getMock();

        $jsonResponseAnnotationResolver
            ->expects($this->once())
            ->method('getReturnJson')
            ->will($this->returnValue(false));

        $event = $this
            ->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getControllerResult',
                'setResponse',
            ))
            ->getMock();

        $event
            ->expects($this->never())
            ->method('getControllerResult');

        $event
            ->expects($this->never())
            ->method('setResponse');

        $jsonResponseAnnotationResolver->onKernelView($event);
    }
}
