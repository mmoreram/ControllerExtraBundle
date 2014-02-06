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

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Mmoreram\ControllerExtraBundle\Annotation\Log as AnnotationLog;

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
     * Tests DefaultManager name method
     */
    public function testDefaultExecute()
    {
        $logAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $defaultExecute = AnnotationLog::EXEC_POST;
        $this->assertInstanceOf('Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver', $logAnnotationResolver->setDefaultExecute($defaultExecute));
        $this->assertEquals($defaultExecute, $logAnnotationResolver->getDefaultExecute());
    }


    /**
     * Tests log message method
     */
    public function testLogMessage()
    {
        $logAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $loggerMethod = 'error';
        $loggerMessage = 'My message';

        $logger = $this
            ->getMockBuilder('Psr\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $logger
            ->expects($this->once())
            ->method($loggerMethod)
            ->with($this->equalTo($loggerMessage));

        $logAnnotationResolver->logMessage($logger, $loggerMethod, $loggerMessage);

    }


    /**
     * Tests level setting evaluateAnnotation method
     *
     * This case considers that Annotation level is not set, so takes config default
     */
    public function testEvaluateAnnotationDefaultLevel()
    {
        $level = 'error';
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
            ))
            ->getMock();

        $annotation
            ->expects($this->once())
            ->method('getLevel')
            ->will($this->returnValue(null));

        $logAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getDefaultLevel',
            ))
            ->getMock();

        $logAnnotationResolver
            ->expects($this->once())
            ->method('getDefaultLevel')
            ->will($this->returnValue($level));

        $logAnnotationResolver->evaluateAnnotation($controller, $request, $annotation, $parametersIndexed);
        $this->assertEquals($logAnnotationResolver->getLevel(), $level);
    }


    /**
     * Tests level setting evaluateAnnotation method
     *
     * This case considers that Annotation level is set, so takes that one
     */
    public function testEvaluateAnnotationAnnotationLevel()
    {
        $level = 'error';
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
            ))
            ->getMock();

        $annotation
            ->expects($this->exactly(2))
            ->method('getLevel')
            ->will($this->returnValue($level));

        $logAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getDefaultLevel',
            ))
            ->getMock();

        $logAnnotationResolver
            ->expects($this->any())
            ->method('getDefaultLevel');

        $logAnnotationResolver->evaluateAnnotation($controller, $request, $annotation, $parametersIndexed);
        $this->assertEquals($logAnnotationResolver->getLevel(), $level);
    }


    /**
     * Tests execute setting evaluateAnnotation method
     *
     * This case considers that Annotation execute is not set, so takes config default
     */
    public function testEvaluateAnnotationDefaultExecute()
    {
        $execute = AnnotationLog::EXEC_POST;
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
                'getExecute',
            ))
            ->getMock();

        $annotation
            ->expects($this->once())
            ->method('getExecute')
            ->will($this->returnValue(null));

        $logAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getDefaultExecute',
            ))
            ->getMock();

        $logAnnotationResolver
            ->expects($this->once())
            ->method('getDefaultExecute')
            ->will($this->returnValue($execute));

        $logAnnotationResolver->evaluateAnnotation($controller, $request, $annotation, $parametersIndexed);
        $this->assertEquals($logAnnotationResolver->getExecute(), $execute);
    }


    /**
     * Tests execute setting evaluateAnnotation method
     *
     * This case considers that Annotation execute is set, so takes that one
     */
    public function testEvaluateAnnotationAnnotationExecute()
    {
        $execute = AnnotationLog::EXEC_POST;
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
                'getExecute',
            ))
            ->getMock();

        $annotation
            ->expects($this->exactly(2))
            ->method('getExecute')
            ->will($this->returnValue($execute));

        $logAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getDefaultExecute',
            ))
            ->getMock();

        $logAnnotationResolver
            ->expects($this->any())
            ->method('getDefaultExecute');

        $logAnnotationResolver->evaluateAnnotation($controller, $request, $annotation, $parametersIndexed);
        $this->assertEquals($logAnnotationResolver->getExecute(), $execute);
    }


    /**
     * Tests evaluateAnnotation method with a both execution
     *
     * This case considers that Annotation is a Flush annotation and no manager is defined in it
     *
     * @param string $execute Execution mode
     */
    public function testEvaluateAnnotationDefaultManagerExecBoth($execute = AnnotationLog::EXEC_BOTH)
    {
        $message = 'My message';
        $level = 'error';
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
                'getExecute',
                'getValue',
            ))
            ->getMock();

        $annotation
            ->expects($this->exactly(2))
            ->method('getLevel')
            ->will($this->returnValue($level));

        $annotation
            ->expects($this->exactly(2))
            ->method('getExecute')
            ->will($this->returnValue($execute));

        $annotation
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue($message));

        $logger = $this
            ->getMockBuilder('Psr\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $logAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getLogger',
                'logMessage',
                'getLevel',
                'getExecute',
                'getValue',
            ))
            ->getMock();

        $logAnnotationResolver
            ->expects($this->once())
            ->method('getLogger')
            ->will($this->returnValue($logger));

        $logAnnotationResolver
            ->expects($this->once())
            ->method('getLevel')
            ->will($this->returnValue($level));

        $logAnnotationResolver
            ->expects($this->once())
            ->method('getExecute')
            ->will($this->returnValue($execute));

        $logAnnotationResolver
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue($message));

        $logAnnotationResolver
            ->expects($this->once())
            ->method('logMessage')
            ->with($this->equalTo($logger), $this->equalTo($level), $this->equalTo($message));

        $logAnnotationResolver->evaluateAnnotation($controller, $request, $annotation, $parametersIndexed);
    }


    /**
     * Tests evaluateAnnotation method with a pre execution
     *
     * This case considers that Annotation is a Flush annotation and no manager is defined in it
     *
     * @param string $execute Execution mode
     */
    public function testEvaluateAnnotationDefaultManagerExecPre()
    {
        $this->testEvaluateAnnotationDefaultManagerExecBoth(AnnotationLog::EXEC_PRE);
    }


    /**
     * Tests onKernelResponse with Exec both
     *
     * This case is with mustLog true
     *
     * @param string $execute Execution mode
     */
    public function testOnKernelResponseMustLogExecBoth($execute = AnnotationLog::EXEC_BOTH)
    {
        $message = 'My message';
        $level = 'error';

        $logAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getLogger',
                'getMustLog',
                'logMessage',
                'getLevel',
                'getExecute',
                'getValue',
            ))
            ->getMock();

        $logger = $this
            ->getMockBuilder('Psr\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $logAnnotationResolver
            ->expects($this->once())
            ->method('getLogger')
            ->will($this->returnValue($logger));

        $logAnnotationResolver
            ->expects($this->once())
            ->method('getMustLog')
            ->will($this->returnValue(true));

        $logAnnotationResolver
            ->expects($this->once())
            ->method('getLevel')
            ->will($this->returnValue($level));

        $logAnnotationResolver
            ->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue($message));

        $logAnnotationResolver
            ->expects($this->once())
            ->method('logMessage')
            ->with($this->equalTo($logger), $this->equalTo($level), $this->equalTo($message));

        $logAnnotationResolver
            ->expects($this->once())
            ->method('getExecute')
            ->will($this->returnValue($execute));

        $event = $this
            ->getMockBuilder('Symfony\Component\HttpKernel\Event\FilterResponseEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $logAnnotationResolver->onKernelResponse($event);
    }


    /**
     * Tests onKernelResponse with Exec post
     *
     * This case is with mustLog true
     *
     * @param string $execute Execution mode
     */
    public function testOnKernelResponseMustLogExecPost()
    {
        $this->testOnKernelResponseMustLogExecBoth(AnnotationLog::EXEC_POST);
    }
}