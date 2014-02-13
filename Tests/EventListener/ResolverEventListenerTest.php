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

namespace Mmoreram\ControllerExtraBundle\Tests\EventListener;

/**
 * Tests ResolverEventListener class
 */
class ResolverEventListenerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var KernelInterface
     *
     * Kernel
     */
    private $kernel;

    /**
     * @var Reader
     *
     * Annotation Reader
     */
    private $reader;

    /**
     * Set up
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
     * Tests add resolver
     */
    public function testAddResolver()
    {
        $resolverEventListener = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\EventListener\ResolverEventListener')
            ->setConstructorArgs(array($this->kernel, $this->reader))
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
