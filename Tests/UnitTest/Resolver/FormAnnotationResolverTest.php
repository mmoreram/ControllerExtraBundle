<?php

/*
 * This file is part of the ControllerExtraBundle for Symfony2.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace Mmoreram\ControllerExtraBundle\Tests\UnitTest\Resolver;

use Mmoreram\ControllerExtraBundle\Annotation\Form;
use Mmoreram\ControllerExtraBundle\Resolver\FormAnnotationResolver;
use Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Form\Type\FakeType;
use PHPUnit_Framework_TestCase;
use ReflectionMethod;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormRegistryInterface;
use Symfony\Component\HttpFoundation\Request;

use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;

/**
 * Tests FormAnnotationResolver class.
 */
class FormAnnotationResolverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var FormAnnotationResolver
     *
     * Form Annotation Resolver
     */
    private $formAnnotationResolver;

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
     * @var FormRegistryInterface
     */
    private $formRegistry;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * Setup method.
     */
    public function setUp()
    {
        $this->formRegistry = $this->getMock('Symfony\Component\Form\FormRegistryInterface');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $requestParameterProvider = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Provider\RequestParameterProvider')
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $requestParameterProvider
            ->expects($this->any())
            ->method('getParameterValue')
            ->will($this->returnValue(''));

        $this->formAnnotationResolver = new FormAnnotationResolver(
            $this->formRegistry,
            $this->formFactory,
            'form'
        );

        $this->request = new Request();

        $this->annotation = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Annotation\Entity')
            ->disableOriginalConstructor()
            ->setMethods([
                'getClass',
                'getName',
            ])
            ->getMock();

        $this->reflectionMethod = new ReflectionMethod(
            'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Controller\FakeController',
            'formAction'
        );
    }

    /**
     * @test
     */
    public function testFQDNUsesFormRegistry()
    {
        $resolvedFormTypeInterface = $this->getMock('Symfony\Component\Form\ResolvedFormTypeInterface');
        $resolvedFormTypeInterface->method('getInnerType')->willReturn(new FakeType());

        $class = 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Form\Type\FakeType';
        $annotation = new Form([
            'class' => $class,
        ]);

        $this
            ->formRegistry
            ->expects($this->once())
            ->method('getType')
            ->with($class)
            ->will($this->returnValue($resolvedFormTypeInterface));

        $this->formAnnotationResolver->evaluateAnnotation($this->request, $annotation, $this->reflectionMethod);
    }
}
