<?php

/**
 * This file is part of the Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 */

namespace Mmoreram\ControllerExtraBundle\Tests\UnitTest\Resolver;

use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit_Framework_TestCase;
use ReflectionMethod;

use Mmoreram\ControllerExtraBundle\Resolver\EntityAnnotationResolver;
use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;
use Mmoreram\ControllerExtraBundle\Provider\EntityProvider;

/**
 * Tests FlushAnnotationResolver class
 */
class EntityAnnotationResolverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var EntityAnnotationResolver
     *
     * Entity Annotation Resolver
     */
    protected $entityAnnotationResolver;

    /**
     * @var Request
     *
     * Request
     */
    protected $request;

    /**
     * @var ParameterBag
     *
     * Request Attributes
     */
    protected $attributes;

    /**
     * @var ReflectionMethod
     *
     * Reflection Method
     */
    protected $reflectionMethod;

    /**
     * @var Annotation
     *
     * Annotation
     */
    protected $annotation;

    /**
     * Setup method
     */
    public function setUp()
    {
        /**
         * @var EntityProvider  $entityProvider
         * @var ManagerRegistry $doctrine
         */
        $entityProvider = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Provider\EntityProvider')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $manager = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $doctrine = $this
            ->getMockBuilder('Symfony\Bridge\Doctrine\ManagerRegistry')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $doctrine
            ->expects($this->any())
            ->method('getManager')
            ->will($this->returnValue($manager));

        $this->entityAnnotationResolver = new EntityAnnotationResolver(
            $doctrine,
            $entityProvider,
            'default',
            '',
            true
        );

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
            ->will($this->returnValue('Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake'));

        $this
            ->annotation
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($name));

        $this
            ->attributes
            ->expects($this->once())
            ->method('set')
            ->with(
                $this->equalTo($resultName),
                $this->isInstanceOf('Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake')
            );

        $this
            ->entityAnnotationResolver
            ->evaluateAnnotation(
                $this->request,
                $this->annotation,
                $this->reflectionMethod
            );
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
        /**
         * @var Annotation $annotation
         */
        $annotation = $this
            ->getMockBuilder($annotationNamespace)
            ->disableOriginalConstructor()
            ->setMethods(array('getClass'))
            ->getMock();

        $annotation
            ->expects($this->exactly($times))
            ->method('getClass')
            ->will($this->returnValue('Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake'));

        $this->entityAnnotationResolver->evaluateAnnotation(
            $this->request,
            $annotation,
            $this->reflectionMethod
        );
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
