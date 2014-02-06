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
     * @var EntityAnnotation
     *
     * Entity Annotation
     */
    private $entityAnnotation;


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

        $this->annotation = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Annotation\Entity')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getNamespace',
            ))
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
            ->method('getNamespace')
            ->will($this->returnValue($entityNamespace));

        $this->entityAnnotationResolver->evaluateAnnotation(array(), $this->request, $this->annotation, array());
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
            ->method('getNamespace')
            ->will($this->returnValue($entityNamespace));

        $entity = $this->entityAnnotationResolver->evaluateAnnotation(array(), $this->request, $this->annotation, array());
        $this->assertInstanceOf('Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\FakeEntity', $entity);
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