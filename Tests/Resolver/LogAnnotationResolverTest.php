<?php

/**
 * Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 */

namespace Mmoreram\ControllerExtraBundle\Tests\Resolver;

/**
 * Tests FlushAnnotationResolver class
 */
class LogAnnotationResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests DefaultManager name method
     */
    public function testDefaultLevel()
    {
        $logAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $defaultLevel = 'error';
        $this->assertInstanceOf('Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver', $logAnnotationResolver->setDefaultLevel($defaultLevel));
        $this->assertEquals($defaultLevel, $logAnnotationResolver->getDefaultLevel());
    }


    /**
     * Tests evaluateAnnotation method
     *
     * This case considers that Annotation is a Flush annotation and no manager is defined in it
     */
    public function testEvaluateAnnotationFlushAnnotationDefaultManager()
    {
        $logAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getLogger',
                'getDefaultLevel',
            ))
            ->getMock();

        $controller = array();
        $parametersIndexed = array();
        $request = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $annotation = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Annotation\Log')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getLevel',
                'getMessage',
            ))
            ->getMock();

        $annotation
            ->expects($this->once())
            ->method('getLevel')
            ->will($this->returnValue(null));

        $annotation
            ->expects($this->once())
            ->method('getMessage')
            ->will($this->returnValue('My message'));

        $logger = $this
            ->getMockBuilder('Psr\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $logger
            ->expects($this->once())
            ->method('error')
            ->with($this->equalTo('My message'));

        $logAnnotationResolver
            ->expects($this->once())
            ->method('getLogger')
            ->will($this->returnValue($logger));

        $logAnnotationResolver
            ->expects($this->once())
            ->method('getDefaultLevel')
            ->will($this->returnValue('error'));

        $logAnnotationResolver->evaluateAnnotation($controller, $request, $annotation, $parametersIndexed);
    }
}