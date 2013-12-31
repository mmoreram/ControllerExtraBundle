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
 * Tests FlushAnnotationResolver class
 */
class FlushAnnotationResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests DefaultManager name method
     */
    public function testDefaultManager()
    {
        $flushAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\FlushAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $defaultManager = 'default';
        $this->assertInstanceOf('Mmoreram\ControllerExtraBundle\Resolver\FlushAnnotationResolver', $flushAnnotationResolver->setDefaultManager($defaultManager));
        $this->assertEquals($defaultManager, $flushAnnotationResolver->getDefaultManager());
    }


    /**
     * Tests evaluateAnnotation method
     *
     * This case considers that Annotation is a Flush annotation and no manager is defined in it
     */
    public function testEvaluateAnnotationFlushAnnotationDefaultManager()
    {
        $flushAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\FlushAnnotationResolver')
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

        $flushAnnotationResolver
            ->expects($this->once())
            ->method('getDoctrine')
            ->will($this->returnValue($doctrine));

        $flushAnnotationResolver
            ->expects($this->once())
            ->method('getDefaultManager')
            ->will($this->returnValue('default'));

        $flushAnnotationResolver->evaluateAnnotation($controller, $request, $annotation, $parametersIndexed);

        $this->assertTrue($flushAnnotationResolver->getMustFlush());
        $this->assertEquals($flushAnnotationResolver->getManager(), $manager);
    }


    /**
     * Tests evaluateAnnotation method
     *
     * This case considers that Annotation is a Flush annotation and specific annotation manager
     */
    public function testEvaluateAnnotationFlushAnnotationSpecificManager()
    {
        $flushAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\FlushAnnotationResolver')
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

        $flushAnnotationResolver
            ->expects($this->once())
            ->method('getDoctrine')
            ->will($this->returnValue($doctrine));

        $flushAnnotationResolver
            ->expects($this->any())
            ->method('getDefaultManager');

        $flushAnnotationResolver->evaluateAnnotation($controller, $request, $annotation, $parametersIndexed);

        $this->assertTrue($flushAnnotationResolver->getMustFlush());
        $this->assertEquals($flushAnnotationResolver->getManager(), $manager);
    }


    /**
     * Tests evaluateAnnotation method
     *
     * This case considers that Annotation is not a Flush annotation
     */
    public function testEvaluateAnnotationWrongAnnotation()
    {
        $flushAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\FlushAnnotationResolver')
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

        $flushAnnotationResolver
            ->expects($this->any())
            ->method('getDoctrine');

        $flushAnnotationResolver->evaluateAnnotation($controller, $request, $annotation, $parametersIndexed);

        $this->assertFalse($flushAnnotationResolver->getMustFlush());
        $this->assertNull($flushAnnotationResolver->getManager());
    }


    /**
     * Tests onKernelResponse method
     *
     * This case considers mustFlush as false
     */
    public function testOnKernelResponseMustFlushFalse()
    {
        $flushAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\FlushAnnotationResolver')
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

        $flushAnnotationResolver
            ->expects($this->once())
            ->method('getMustFlush')
            ->will($this->returnValue(false));

        $flushAnnotationResolver->onKernelResponse($event);
    }


    /**
     * Tests onKernelResponse method
     *
     * This case considers mustFlush as false
     */
    public function testOnKernelResponseMustFlushTrue()
    {
        $flushAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\FlushAnnotationResolver')
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

        $flushAnnotationResolver
            ->expects($this->once())
            ->method('getMustFlush')
            ->will($this->returnValue(true));

        $flushAnnotationResolver
            ->expects($this->once())
            ->method('getManager')
            ->will($this->returnValue($manager));

        $flushAnnotationResolver->onKernelResponse($event);
    }
}