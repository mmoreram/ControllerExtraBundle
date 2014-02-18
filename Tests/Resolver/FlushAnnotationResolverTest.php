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

use Symfony\Component\HttpFoundation\ParameterBag;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tests FlushAnnotationResolver class
 */
class FlushAnnotationResolverTest extends \PHPUnit_Framework_TestCase
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
     * Setup method
     */
    public function setUp()
    {
        $this->flushAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\FlushAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getDoctrine',
                'getDefaultManager',
            ))
            ->getMock();

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
            ->expects($this->any())
            ->method('getManager')
            ->will($this->returnValue($manager));

        $this->flushAnnotationResolver
            ->expects($this->any())
            ->method('getDoctrine')
            ->will($this->returnValue($doctrine));

        $this->flushAnnotationResolver
            ->expects($this->any())
            ->method('getDefaultManager')
            ->will($this->returnValue('default'));

        $this->request = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->request->attributes = new ParameterBag();

        $this->reflectionMethod = $this
            ->getMockBuilder('ReflectionMethod')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Tests DefaultManager name method
     *
     * @param string $annotationManager Manager defined in annotation
     * @param string $resultManager     Result Manager
     *
     * @dataProvider dataManager
     */
    public function testManager($annotationManager, $resultManager)
    {
        $doctrine = $this
            ->getMockBuilder('Symfony\Bridge\Doctrine\ManagerRegistry')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getManager',
            ))
            ->getMock();

        $doctrine
            ->expects($this->any())
            ->method('getManager')
            ->will($this->returnValue($resultManager));

        $this->flushAnnotationResolver
            ->expects($this->any())
            ->method('getDoctrine')
            ->will($this->returnValue($doctrine));

        $annotation = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Annotation\Flush')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getManager',
            ))
            ->getMock();

        $annotation
            ->expects($this->any())
            ->method('getManager')
            ->will($this->returnValue($annotationManager));

        $this->flushAnnotationResolver->evaluateAnnotation($this->request, $annotation, $this->reflectionMethod);
    }

    /**
     * Data for testManager
     *
     * @return array data
     */
    public function dataManager()
    {
        return array(

            array(null, 'default'),
            array(false, 'default'),
            array('', 'default'),
            array('main', 'main'),
            array('default', 'default')
        );
    }

    /**
     * Tests entity definition.
     *
     * Given every possible possibility, test how this resolver uses defined
     * annotation data
     *
     * @param array $entities          Entities
     * @param array $requestAttributes Request attributes
     * @param array $flushedEntities   Flushed entities
     *
     * @dataProvider dataEntities
     */
    public function testEntities($entities, $requestAttributes, $flushedEntities)
    {

        $annotation = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Annotation\Flush')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getEntity',
            ))
            ->getMock();

        $annotation
            ->expects($this->once())
            ->method('getEntity')
            ->will($this->returnValue($entities));

        $this->request->attributes = new ParameterBag($requestAttributes);

        $this->flushAnnotationResolver->evaluateAnnotation($this->request, $annotation, $this->reflectionMethod);
        $this->assertEquals($flushedEntities, $this->flushAnnotationResolver->getEntities());
    }

    /**
     * Data provider for testEntities method
     *
     * @return array data
     */
    public function dataEntities()
    {
        return array(

            array(
                null, [], null,
            ),
            array(
                'entity',
                ['entity' => 'entity_value'],
                new ArrayCollection(['entity_value']),
            ),
            array(
                ['entity'],
                ['entity' => 'entity_value'],
                new ArrayCollection(['entity_value']),
            ),
            array(
                ['entity'],
                [],
                null,
            ),
            array(
                ['entity', 'entity2'],
                ['entity2' => 'entity2_value'],
                new ArrayCollection(['entity2_value']),
            ),
        );
    }

    /**
     * Tests Annotation type
     *
     * @param string  $annotationNamespace Annotation namespace
     * @param boolean $mustFlush           Must flush
     *
     * @dataProvider dataAnnotationNamespace
     */
    public function testAnnotationNamespace($annotationNamespace, $mustFlush)
    {
        $annotation = $this
            ->getMockBuilder($annotationNamespace)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $this->flushAnnotationResolver->evaluateAnnotation($this->request, $annotation, $this->reflectionMethod);
        $this->assertEquals($mustFlush, $this->flushAnnotationResolver->getMustFlush());
    }

    /**
     * Data for testAnnotationNamespace
     *
     * @return array data
     */
    public function dataAnnotationNamespace()
    {
        return array(

            array('Mmoreram\ControllerExtraBundle\Annotation\Flush', true),
            array('Mmoreram\ControllerExtraBundle\Annotation\Log', false),
            array('Mmoreram\ControllerExtraBundle\Annotation\Form', false),
            array('Mmoreram\ControllerExtraBundle\Annotation\Entity', false),
        );
    }

    /**
     * onKernelResponse method
     */

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
