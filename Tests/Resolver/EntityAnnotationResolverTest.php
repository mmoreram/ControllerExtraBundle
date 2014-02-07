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

use Mmoreram\ControllerExtraBundle\Tests\Fixtures\FakeEntity;

/**
 * Tests FlushAnnotationResolver class
 */
class EntityAnnotationResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var EntityAnnotationResolver
     *
     * Entity Annotation Resolver
     */
    private $entityAnnotationResolver;


    /**
     * @var Request
     *
     * Request
     */
    private $request;


    /**
     * @var ParameterBag
     * 
     * Request Attributes
     */
    private $attributes;


    /**
     * @var EntityAnnotation
     *
     * Entity Annotation
     */
    private $entityAnnotation;


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
        $bundle = $this
            ->getMockBuilder('Symfony\Component\HttpKernel\Bundle\Bundle')
            ->disableOriginalConstructor()
            ->setMethods(array('getNamespace'))
            ->getMock();

        $bundle
            ->expects($this->any())
            ->method('getNamespace')
            ->will($this->returnValue('Mmoreram\ControllerExtraBundle\Tests\FakeBundle'));

        $kernelBundles = array(
            'FakeBundle'    =>  $bundle
        );

        $this->entityAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\EntityAnnotationResolver')
            ->setConstructorArgs(array($kernelBundles))
            ->setMethods(null)
            ->getMock();

        $this->request = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->attributes = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\ParameterBag')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'set',
            ))
            ->getMock();

        $this->request->attributes = $this->attributes;

        $this->annotation = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Annotation\Entity')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getClass',
                'getName',
            ))
            ->getMock();

        $this->reflectionMethod = $this
            ->getMockBuilder('ReflectionMethod')
            ->disableOriginalConstructor()
            ->getMock();
    }


    /**
     * Tests good entity definition
     *
     * @dataProvider goodEntityDataProvider
     */
    public function testGoodEntityDefinition($entityNamespace)
    {

        $this
            ->annotation
            ->expects($this->once())
            ->method('getClass')
            ->will($this->returnValue($entityNamespace));

        $this
            ->annotation
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('entity'));

        $this
            ->attributes
            ->expects($this->once())
            ->method('set')
            ->with($this->equalTo('entity'), $this->isInstanceOf('Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\FakeEntity'));

        $this
            ->entityAnnotationResolver
            ->evaluateAnnotation($this->request, $this->annotation, $this->reflectionMethod);
    }


    /**
     * Tests wrong entity definition
     *
     * @dataProvider badEntityDataProvider
     * @expectedException Mmoreram\ControllerExtraBundle\Exceptions\EntityNotFoundException
     */
    public function testWrongEntityDefinition($entityNamespace)
    {

        $this
            ->annotation
            ->expects($this->once())
            ->method('getClass')
            ->will($this->returnValue($entityNamespace));

        $this
            ->annotation
            ->expects($this->never())
            ->method('getName');

        $this
            ->entityAnnotationResolver
            ->evaluateAnnotation($this->request, $this->annotation, $this->reflectionMethod);
    }


    /**
     * Good Entity definition data provider
     *
     * @return array Good entity definition array
     */
    public function goodEntityDataProvider()
    {
        return array(
            array('FakeBundle:FakeEntity'),
        );
    }


    /**
     * Good Entity definition data provider
     *
     * @return array Good entity definition array
     */
    public function badEntityDataProvider()
    {
        return array(
            array(null),
            array(''),
            array(':'),
            array('FakeBundle:'),
            array('FakeBundle:AnotherEntity'),
            array(':FakeEntity'),
            array('AnotherBundle:FakeEntity'),
        );
    }
}