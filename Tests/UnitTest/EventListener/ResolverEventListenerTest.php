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

namespace Mmoreram\ControllerExtraBundle\Tests\UnitTest\EventListener;

use Doctrine\Common\Annotations\Reader;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpKernel\KernelInterface;

use Mmoreram\ControllerExtraBundle\EventListener\ResolverEventListener;

/**
 * Tests ResolverEventListener class.
 */
class ResolverEventListenerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var KernelInterface
     *
     * Kernel
     */
    protected $kernel;

    /**
     * @var Reader
     *
     * Annotation Reader
     */
    protected $reader;

    /**
     * Set up.
     */
    public function setUp()
    {
        $this->kernel = $this
            ->getMockBuilder('Symfony\Component\HttpKernel\KernelInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->reader = $this
            ->getMockBuilder('Doctrine\Common\Annotations\Reader')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Tests add resolver.
     */
    public function testAddResolver()
    {
        /**
         * @var ResolverEventListener $resolverEventListener
         */
        $resolverEventListener = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\EventListener\ResolverEventListener')
            ->setConstructorArgs([$this->kernel, $this->reader])
            ->setMethods(null)
            ->getMock();

        $resolver = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Resolver\Interfaces\AnnotationResolverInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $resolverEventListener->addResolver($resolver);

        $this->assertCount(1, $resolverEventListener->getResolverStack());
    }
}
