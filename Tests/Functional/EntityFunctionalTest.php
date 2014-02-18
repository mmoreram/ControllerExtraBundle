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

namespace Mmoreram\ControllerExtraBundle\Tests\Functional;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\Request;

use Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Controller\FakeController;
use Mmoreram\ControllerExtraBundle\EventListener\ResolverEventListener;
use Mmoreram\ControllerExtraBundle\Resolver\EntityAnnotationResolver;

/**
 * Tests FlushAnnotationResolver class
 */
class EntityFunctionalTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Trying entity annotation
     */
    public function testFunctionalEntityAnnotation()
    {

        AnnotationRegistry::registerFile(dirname(__FILE__) . '/../../Annotation/Entity.php');

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

        $doctrine = $this->getMock('Symfony\Bridge\Doctrine\ManagerRegistry');

        $doctrine = $this
            ->getMockBuilder('Doctrine\Common\Persistence\AbstractManagerRegistry')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getService',
                'resetService',
                'getContainer',
                'getAliasNamespace',
            ))
            ->getMock();

        $entityAnnotationResolver = new EntityAnnotationResolver($doctrine, $kernelBundles, 'entity', 'default', false);
        $kernel  = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $request = new Request();
        $reader = new AnnotationReader();
        $event = new FilterControllerEvent($kernel, array(new FakeController(), 'entityMethod'), $request, null);
        $resolverEventListener = new ResolverEventListener($kernel, $reader);
        $resolverEventListener->addResolver($entityAnnotationResolver);
        $resolverEventListener->onKernelController($event);

        $this->assertInstanceOf(
            'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\FakeEntity',
            $request->attributes->get('entityName')
        );

        $this->assertInstanceOf(
            'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\FakeEntity',
            $request->attributes->get('entity')
        );
    }
}
