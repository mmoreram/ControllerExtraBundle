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

use Mmoreram\ControllerExtraBundle\Annotation\Log as AnnotationLog;

/**
 * Tests FlushAnnotationResolver class
 */
class LogAnnotationResolverTest extends \PHPUnit_Framework_TestCase
{

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
        $this->request = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->reflectionMethod = $this
            ->getMockBuilder('ReflectionMethod')
            ->disableOriginalConstructor()
            ->getMock();

        $this->annotation = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Annotation\Log')
            ->disableOriginalConstructor()
            ->getMock();
    }

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
        $this->assertInstanceOf(
            'Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver',
            $logAnnotationResolver->setDefaultLevel($defaultLevel)
        );
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
        $this->assertInstanceOf(
            'Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver',
            $logAnnotationResolver->setDefaultExecute($defaultExecute)
        );
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
     * Tests level setting in evaluateAnnotation method
     *
     * @param string $defaultLevel Default level
     * @param string $level        Level
     * @param string $resultLevel  Result level
     *
     * @dataProvider dataEvaluateAnnotationLevel
     */
    public function testEvaluateAnnotationLevel($defaultLevel, $level, $resultLevel)
    {

        $this->annotation
            ->expects($this->any())
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
            ->method('getDefaultLevel')
            ->will($this->returnValue($defaultLevel));

        $logAnnotationResolver->evaluateAnnotation($this->request, $this->annotation, $this->reflectionMethod);
        $this->assertEquals($logAnnotationResolver->getLevel(), $resultLevel);
    }

    /**
     * testEvaluateAnnotationLevel data provider
     *
     * @return array Data
     */
    public function dataEvaluateAnnotationLevel()
    {
        return array(
            array('info', null, 'info'),
            array('info', false, 'info'),
            array('info', 'info', 'info'),
            array('info', 'error', 'error'),
        );
    }

    /**
     * Tests execute setting in evaluateAnnotation method
     *
     * @param string $defaultExecute Default execute
     * @param string $execute        Execute
     * @param string $resultExecute  Result execute
     *
     * @dataProvider dataEvaluateAnnotationExecute
     */
    public function testEvaluateAnnotationExecute($defaultExecute, $execute, $resultExecute)
    {

        $this->annotation
            ->expects($this->any())
            ->method('getExecute')
            ->will($this->returnValue($execute));

        $logAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getDefaultExecute',
                'logMessage',
                'getLogger',
            ))
            ->getMock();

        $logAnnotationResolver
            ->expects($this->any())
            ->method('getDefaultExecute')
            ->will($this->returnValue($defaultExecute));

        $logger = $this
            ->getMockBuilder('Psr\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $logAnnotationResolver
            ->expects($this->any())
            ->method('getLogger')
            ->will($this->returnValue($logger));

        $logAnnotationResolver->evaluateAnnotation($this->request, $this->annotation, $this->reflectionMethod);
        $this->assertEquals($logAnnotationResolver->getExecute(), $resultExecute);
    }

    /**
     * testEvaluateAnnotationLevel data provider
     *
     * @return array Data
     */
    public function dataEvaluateAnnotationExecute()
    {
        return array(
            array(AnnotationLog::EXEC_POST, null, AnnotationLog::EXEC_POST),
            array(AnnotationLog::EXEC_POST, false, AnnotationLog::EXEC_POST),
            array(AnnotationLog::EXEC_POST, AnnotationLog::EXEC_POST, AnnotationLog::EXEC_POST),
            array(AnnotationLog::EXEC_POST, AnnotationLog::EXEC_PRE, AnnotationLog::EXEC_PRE),
        );
    }

    /**
     * Tests evaluateAnnotation method with a both execution
     *
     * This case considers that Annotation is a Flush annotation and no manager
     * is defined in it
     *
     * @param string  $execute            Execute
     * @param boolean $logMessageIsCalled Log message is called
     *
     * @dataProvider dataEvaluateAnnotation
     */
    public function testEvaluateAnnotation($execute, $logMessageIsCalled)
    {
        $message = 'My message';
        $level = 'error';

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
            ->expects($this->any())
            ->method('getLogger')
            ->will($this->returnValue($logger));

        $logAnnotationResolver
            ->expects($this->any())
            ->method('getLevel')
            ->will($this->returnValue($level));

        $logAnnotationResolver
            ->expects($this->any())
            ->method('getExecute')
            ->will($this->returnValue($execute));

        $logAnnotationResolver
            ->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue($message));

        if ($logMessageIsCalled) {

            $logAnnotationResolver
                ->expects($this->atLeastOnce())
                ->method('logMessage')
                ->with($this->equalTo($logger), $this->equalTo($level), $this->equalTo($message));
        } else {

            $logAnnotationResolver
                ->expects($this->never())
                ->method('logMessage');
        }

        $logAnnotationResolver->evaluateAnnotation($this->request, $this->annotation, $this->reflectionMethod);
    }

    /**
     * testEvaluateAnnotationLevel data provider
     *
     * @return array Data
     */
    public function dataEvaluateAnnotation()
    {
        return array(
            array(AnnotationLog::EXEC_PRE, true),
            array(AnnotationLog::EXEC_BOTH, true),
            array(AnnotationLog::EXEC_POST, false),
        );
    }

    /**
     * Tests onKernelResponse with Exec both
     *
     * This case is with mustLog true
     *
     * @param string  $execute            Execute
     * @param boolean $mustLog            Must log
     * @param boolean $logMessageIsCalled Log message is called
     *
     * @dataProvider dataOnKernelResponse
     */
    public function testOnKernelResponse($execute, $mustLog, $logMessageIsCalled)
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
            ->expects($this->any())
            ->method('getLogger')
            ->will($this->returnValue($logger));

        $logAnnotationResolver
            ->expects($this->any())
            ->method('getMustLog')
            ->will($this->returnValue($mustLog));

        $logAnnotationResolver
            ->expects($this->any())
            ->method('getLevel')
            ->will($this->returnValue($level));

        $logAnnotationResolver
            ->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue($message));

        $logAnnotationResolver
            ->expects($this->any())
            ->method('getExecute')
            ->will($this->returnValue($execute));

        if ($logMessageIsCalled) {

            $logAnnotationResolver
                ->expects($this->atLeastOnce())
                ->method('logMessage')
                ->with($this->equalTo($logger), $this->equalTo($level), $this->equalTo($message));
        } else {

            $logAnnotationResolver
                ->expects($this->never())
                ->method('logMessage');
        }

        $event = $this
            ->getMockBuilder('Symfony\Component\HttpKernel\Event\FilterResponseEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $logAnnotationResolver->onKernelResponse($event);
    }

    /**
     * testEvaluateAnnotationLevel data provider
     *
     * @return array Data
     */
    public function dataOnKernelResponse()
    {
        return array(
            array(AnnotationLog::EXEC_PRE, true, false),
            array(AnnotationLog::EXEC_PRE, false, false),
            array(AnnotationLog::EXEC_BOTH, true, true),
            array(AnnotationLog::EXEC_BOTH, false, false),
            array(AnnotationLog::EXEC_POST, true, true),
            array(AnnotationLog::EXEC_POST, false, false),
        );
    }

    /**
     * Tests Annotation type
     *
     * @param string  $annotationNamespace Annotation namespace
     * @param integer $times               Times getClass will be called
     *
     * @dataProvider dataAnnotationNamespace
     */
    public function testAnnotationNamespace($annotationNamespace, $times)
    {
        $logAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $annotation = $this
            ->getMockBuilder($annotationNamespace)
            ->disableOriginalConstructor()
            ->setMethods(array('getLevel'))
            ->getMock();

        $annotation
            ->expects($this->exactly($times))
            ->method('getLevel');

        $logAnnotationResolver->evaluateAnnotation($this->request, $annotation, $this->reflectionMethod);
    }

    /**
     * Data for testAnnotationNamespace
     *
     * @return array data
     */
    public function dataAnnotationNamespace()
    {
        return array(

            array('Mmoreram\ControllerExtraBundle\Annotation\Log', 1),
            array('Mmoreram\ControllerExtraBundle\Annotation\Entity', 0),
            array('Mmoreram\ControllerExtraBundle\Annotation\Flush', 0),
            array('Mmoreram\ControllerExtraBundle\Annotation\Form', 0),
        );
    }
}
