<?php

/**
 * This file is part of the ControllerExtraBundle for Symfony2.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace Mmoreram\ControllerExtraBundle\Tests\Functional\Resolver;

use Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory;
use Mmoreram\ControllerExtraBundle\Tests\Functional\AbstractWebTestCase;

/**
 * Class PaginatorResolverTest
 */
class PaginatorAnnotationResolverTest extends AbstractWebTestCase
{
    /**
     * testAnnotation
     */
    public function testAnnotation()
    {
        $this->client->request('GET', '/fake/paginator/updatedAt/2/5/10');

        $this->assertEquals(
            '{"dql":"SELECT x, r4, r5 FROM Mmoreram\\\\ControllerExtraBundle\\\\Tests\\\\FakeBundle\\\\Entity\\\\Fake x INNER JOIN x.relation3 r3 INNER JOIN x.relation4 r4 LEFT JOIN x.relation r LEFT JOIN x.relation2 r2 LEFT JOIN x.relation5 r5 WHERE x.enabled = ?00 AND x.address1 IS NOT NULL AND x.address2 IS NOT NULL ORDER BY x.createdAt ASC, x.id ASC"}',
            $this
                ->client
                ->getResponse()
                ->getContent()
        );
    }

    /**
     * Test paginator simple
     */
    public function testPaginatorSimpleAnnotation()
    {
        $fake = FakeFactory::createStatic();
        $fake->setField('');
        $entityManager = static::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getManagerForClass('Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake');

        $entityManager->persist($fake);
        $entityManager->flush();

        $this
            ->client
            ->request(
                'GET',
                '/fake/paginator/simple/id/2/1/5'
            );

        $this->assertEquals(
            '{"count":1}',
            $this
                ->client
                ->getResponse()
                ->getContent()
        );
    }

    /**
     * Test paginator not matching
     */
    public function testPaginatorNotMatchingAnnotation()
    {
        $fake = FakeFactory::createStatic();
        $fake->setField('');
        $entityManager = static::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getManagerForClass('Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake');

        $entityManager->persist($fake);
        $entityManager->flush();

        $this
            ->client
            ->request(
                'GET',
                '/fake/paginator/notmatching/id/2/1/5'
            );

        $this->assertEquals(
            '{"count":0}',
            $this
                ->client
                ->getResponse()
                ->getContent()
        );
    }
}
