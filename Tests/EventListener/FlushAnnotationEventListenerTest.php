<?php

/**
 * Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 */

namespace Mmoreram\ControllerExtraBundle\Tests\EventListener;

/**
 * Tests FlushAnnotationEventListener class
 */
class FlushAnnotationEventListenerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests DefaultManager name method
     */
    public function testDefaultManager()
    {
        $flushAnnotationEventListener = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\EventListener\FlushAnnotationEventListener')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $defaultManager = 'default';
        $this->assertInstanceOf('Mmoreram\ControllerExtraBundle\EventListener\FlushAnnotationEventListener', $flushAnnotationEventListener->setDefaultManager($defaultManager));
        $this->assertEquals($defaultManager, $flushAnnotationEventListener->getDefaultManager());
    }


    /**
     * Tests evaluateAnnotation method
     *
     * This case considers that Annotation is a Flush annotation and no manager is defined in it
     */
    public function testEvaluateAnnotationFlushAnnotationDefaultManager()
    {
        $flushAnnotationEventListener = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\EventListener\FlushAnnotationEventListener')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getDoctrine',
                'getDefaultManager',
            ))
            ->getMock();

        $controller = array();
        $parametersIndexed = array();
        $request = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $annotation = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Annotation\Flush')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getManager',
            ))
            ->getMock();

        $annotation
            ->expects($this->once())
            ->method('getManager')
            ->will($this->returnValue(null));

        $manager = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $doctrine = $this
            ->getMockBuilder('Symfony\Bridge\Doctrine\ManagerRegistry')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getManager',
            ))
            ->getMock();

        $doctrine
            ->expects($this->once())
            ->method('getManager')
            ->will($this->returnValue($manager));

        $flushAnnotationEventListener
            ->expects($this->once())
            ->method('getDoctrine')
            ->will($this->returnValue($doctrine));

        $flushAnnotationEventListener
            ->expects($this->once())
            ->method('getDefaultManager')
            ->will($this->returnValue('default'));

        $flushAnnotationEventListener->evaluateAnnotation($controller, $request, $annotation, $parametersIndexed);

        $this->assertTrue($flushAnnotationEventListener->getMustFlush());
        $this->assertEquals($flushAnnotationEventListener->getManager(), $manager);
    }


    /**
     * Tests evaluateAnnotation method
     *
     * This case considers that Annotation is a Flush annotation and specific annotation manager
     */
    public function testEvaluateAnnotationFlushAnnotationSpecificManager()
    {
        $flushAnnotationEventListener = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\EventListener\FlushAnnotationEventListener')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getDoctrine',
            ))
            ->getMock();

        $controller = array();
        $parametersIndexed = array();
        $request = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $annotation = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Annotation\Flush')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getManager',
            ))
            ->getMock();

        $annotation
            ->expects($this->exactly(2))
            ->method('getManager')
            ->will($this->returnValue('default'));

        $manager = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $doctrine = $this
            ->getMockBuilder('Symfony\Bridge\Doctrine\ManagerRegistry')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getManager',
            ))
            ->getMock();

        $doctrine
            ->expects($this->once())
            ->method('getManager')
            ->will($this->returnValue($manager));

        $flushAnnotationEventListener
            ->expects($this->once())
            ->method('getDoctrine')
            ->will($this->returnValue($doctrine));

        $flushAnnotationEventListener
            ->expects($this->any())
            ->method('getDefaultManager');

        $flushAnnotationEventListener->evaluateAnnotation($controller, $request, $annotation, $parametersIndexed);

        $this->assertTrue($flushAnnotationEventListener->getMustFlush());
        $this->assertEquals($flushAnnotationEventListener->getManager(), $manager);
    }


    /**
     * Tests evaluateAnnotation method
     *
     * This case considers that Annotation is not a Flush annotation
     */
    public function testEvaluateAnnotationWrongAnnotation()
    {
        $flushAnnotationEventListener = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\EventListener\FlushAnnotationEventListener')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getDoctrine',
            ))
            ->getMock();

        $controller = array();
        $parametersIndexed = array();
        $request = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $annotation = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation')
            ->disableOriginalConstructor()
            ->getMock();

        $flushAnnotationEventListener
            ->expects($this->any())
            ->method('getDoctrine');

        $flushAnnotationEventListener->evaluateAnnotation($controller, $request, $annotation, $parametersIndexed);

        $this->assertFalse($flushAnnotationEventListener->getMustFlush());
        $this->assertNull($flushAnnotationEventListener->getManager());
    }


    /**
     * Tests onKernelResponse method
     *
     * This case considers mustFlush as false
     */
    public function testOnKernelResponseMustFlushFalse()
    {
        $flushAnnotationEventListener = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\EventListener\FlushAnnotationEventListener')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getMustFlush',
            ))
            ->getMock();

        $event = $this
            ->getMockBuilder('Symfony\Component\HttpKernel\Event\FilterResponseEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $manager = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'flush',
            ))
            ->getMock();

        $manager
            ->expects($this->any())
            ->method('flush');

        $flushAnnotationEventListener
            ->expects($this->once())
            ->method('getMustFlush')
            ->will($this->returnValue(false));

        $flushAnnotationEventListener->onKernelResponse($event);
    }


    /**
     * Tests onKernelResponse method
     *
     * This case considers mustFlush as false
     */
    public function testOnKernelResponseMustFlushTrue()
    {
        $flushAnnotationEventListener = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\EventListener\FlushAnnotationEventListener')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getMustFlush',
                'getManager',
            ))
            ->getMock();

        $event = $this
            ->getMockBuilder('Symfony\Component\HttpKernel\Event\FilterResponseEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $manager = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'flush',
            ))
            ->getMock();

        $manager
            ->expects($this->once())
            ->method('flush');

        $flushAnnotationEventListener
            ->expects($this->once())
            ->method('getMustFlush')
            ->will($this->returnValue(true));

        $flushAnnotationEventListener
            ->expects($this->once())
            ->method('getManager')
            ->will($this->returnValue($manager));

        $flushAnnotationEventListener->onKernelResponse($event);
    }
}