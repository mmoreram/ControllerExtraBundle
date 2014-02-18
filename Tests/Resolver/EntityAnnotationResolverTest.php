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

        $this->entityAnnotationResolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\EntityAnnotationResolver')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getDoctrine',
                'getKernelBundles',
                'getDefaultName',
                'getDefaultPersist'
            ))
            ->getMock();

        $manager = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'persist',
            ))
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

        $this
            ->entityAnnotationResolver
            ->expects($this->any())
            ->method('getDoctrine')
            ->will($this->returnValue($doctrine));

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

        $this
            ->entityAnnotationResolver
            ->expects($this->any())
            ->method('getKernelBundles')
            ->will($this->returnValue($kernelBundles));

        $this
            ->entityAnnotationResolver
            ->expects($this->any())
            ->method('getDefaultName')
            ->will($this->returnValue('default'));

        $this
            ->entityAnnotationResolver
            ->expects($this->any())
            ->method('getDefaultPersist')
            ->will($this->returnValue(true));

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
     * @param string $entityNamespace Entity namespace
     *
     * @dataProvider dataGoodEntityDefinition
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
     * Good Entity definition data provider
     *
     * @return array Good entity definition array
     */
    public function dataGoodEntityDefinition()
    {
        return array(
            array('FakeBundle:FakeEntity'),
        );
    }

    /**
     * Tests wrong entity definition
     *
     * @param string $entityNamespace Entity namespace
     *
     * @dataProvider dataWrongEntityDefinition
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
    public function dataWrongEntityDefinition()
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

    /**
     * Tests field name
     *
     * @param string $name       Name
     * @param string $resultName Result name
     *
     * @dataProvider dataName
     */
    public function testName($name, $resultName)
    {
        $this
            ->annotation
            ->expects($this->any())
            ->method('getClass')
            ->will($this->returnValue('FakeBundle:FakeEntity'));

        $this
            ->annotation
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($name));

        $this
            ->attributes
            ->expects($this->once())
            ->method('set')
            ->with($this->equalTo($resultName), $this->isInstanceOf('Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\FakeEntity'));

        $this
            ->entityAnnotationResolver
            ->evaluateAnnotation($this->request, $this->annotation, $this->reflectionMethod);
    }

    /**
     * Data name data provider
     *
     * @return array Data name array
     */
    public function dataName()
    {
        return array(
            array(null, 'default'),
            array(false, 'default'),
            array('', 'default'),
            array('default', 'default'),
            array('myEntity', 'myEntity'),
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
        $annotation = $this
            ->getMockBuilder($annotationNamespace)
            ->disableOriginalConstructor()
            ->setMethods(array('getClass'))
            ->getMock();

        $annotation
            ->expects($this->exactly($times))
            ->method('getClass')
            ->will($this->returnValue('FakeBundle:FakeEntity'));

        $this->entityAnnotationResolver->evaluateAnnotation($this->request, $annotation, $this->reflectionMethod);
    }

    /**
     * Data for testAnnotationNamespace
     *
     * @return array data
     */
    public function dataAnnotationNamespace()
    {
        return array(

            array('Mmoreram\ControllerExtraBundle\Annotation\Entity', 1),
            array('Mmoreram\ControllerExtraBundle\Annotation\Flush', 0),
            array('Mmoreram\ControllerExtraBundle\Annotation\Log', 0),
            array('Mmoreram\ControllerExtraBundle\Annotation\Form', 0),
        );
    }
}
