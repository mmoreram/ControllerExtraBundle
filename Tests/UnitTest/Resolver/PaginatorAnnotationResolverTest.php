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

use Doctrine\ORM\Tools\Pagination\Paginator;

use Mmoreram\ControllerExtraBundle\Resolver\PaginatorAnnotationResolver;

/**
 * Class PaginatorAnnotationResolverTest.
 */
class PaginatorAnnotationResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get resolver.
     *
     * @param string $defaultName
     * @param int    $defaultPage
     * @param int    $defaultLimit
     *
     * @return PaginatorAnnotationResolver
     */
    private function getResolver($defaultName = 'test', $defaultPage = 1, $defaultLimit = 10)
    {
        $doctrine = $this
            ->getMockBuilder('Doctrine\Common\Persistence\AbstractManagerRegistry')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $entityProvider = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Provider\EntityProvider')
            ->disableOriginalConstructor()
            ->getMock();

        $paramProvider = $this
            ->getMockBuilder('Mmoreram\ControllerExtraBundle\Provider\RequestParameterProvider')
            ->disableOriginalConstructor()
            ->getMock();

        $evaluator = $this->createMock('Mmoreram\ControllerExtraBundle\Resolver\Paginator\PaginatorEvaluatorCollector');

        return new PaginatorAnnotationResolver(
            $doctrine,
            $entityProvider,
            $paramProvider,
            $evaluator,
            $defaultName,
            $defaultPage,
            $defaultLimit
        );
    }

    /**
     * Get mock paginator.
     *
     * @return Paginator
     */
    private function getMockPaginator()
    {
        return $this
            ->getMockBuilder('Doctrine\ORM\Tools\Pagination\Paginator')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Test KNPPaginator resolves correctly.
     */
    public function testKNPPaginatorResolving()
    {
        if (defined('HHVM_VERSION') && HHVM_VERSION_ID < 30800) {
            $this->markTestSkipped('This test fails on HHVM < 3.8.0');
        }

        $resolver = $this->getResolver();
        $mockPaginator = $this->getMockPaginator();

        $mockPaginator
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn([]);

        $paginator = $resolver
            ->decidePaginatorFormat(
                $mockPaginator,
                'Knp\Component\Pager\Pagination\PaginationInterface',
                1,
                10
            );

        $this->assertInstanceOf(
            'Knp\Component\Pager\Pagination\PaginationInterface',
            $paginator
        );
    }
}
