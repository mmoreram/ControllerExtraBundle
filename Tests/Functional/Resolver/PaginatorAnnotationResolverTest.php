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

namespace Mmoreram\ControllerExtraBundle\Tests\Functional\Resolver;

use Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory;
use Mmoreram\ControllerExtraBundle\Tests\Functional\AbstractWebTestCase;

/**
 * Class PaginatorResolverTest.
 */
class PaginatorAnnotationResolverTest extends AbstractWebTestCase
{
    /**
     * testAnnotation.
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
     * Test paginator simple.
     */
    public function testPaginatorSimpleAnnotation()
    {
        $fake = FakeFactory::create();
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
     * Test paginator not matching.
     */
    public function testPaginatorNotMatchingAnnotation()
    {
        $fake = FakeFactory::create();
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

    /**
     * Test paginator with attributes.
     */
    public function testPaginatorAnnotationAttributes()
    {
        $entityManager = static::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getManagerForClass('Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake');

        for ($i = 0; $i < 30; ++$i) {
            $fake = FakeFactory::create();
            $fake->setField('');
            $entityManager->persist($fake);
        }
        $entityManager->flush();

        $this
            ->client
            ->request(
                'GET',
                '/fake/paginator/attributes/id/2/1/5'
            );

        $response = json_decode($this
            ->client
            ->getResponse()
            ->getContent(), true);

        $this->assertEquals(6, $response['totalPages']);
        $this->assertEquals(29, $response['totalElements']);
        $this->assertEquals(1, $response['currentPage']);
    }

    /**
     * Test paginator with pagerfanta.
     */
    public function testPaginatorAnnotationPagerfanta()
    {
        $fake = FakeFactory::create();
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
                '/fake/paginator/pagerfanta/id/2/1/5'
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
     * Test paginator with knppaginator.
     */
    public function testPaginatorAnnotationKNPPaginator()
    {
        $fake = FakeFactory::create();
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
                '/fake/paginator/knppaginator/id/2/1/5'
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
     * Test paginator with query.
     */
    public function testPaginatorAnnotationQuery()
    {
        $entityManager = static::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getManagerForClass('Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake');

        for ($i = 0; $i < 30; ++$i) {
            $fake = FakeFactory::create();
            $fake->setField('');
            $entityManager->persist($fake);
        }
        $entityManager->flush();

        $this
            ->client
            ->request(
                'GET',
                '/fake/paginator/query?limit=7&page=3'
            );

        $response = json_decode($this
            ->client
            ->getResponse()
            ->getContent(), true);

        $this->assertEquals(5, $response['totalPages']);
        $this->assertEquals(30, $response['totalElements']);
        $this->assertEquals(3, $response['currentPage']);
        $this->assertEquals(7, $response['count']);
    }

    /**
     * Test paginator with request.
     */
    public function testPaginatorAnnotationRequest()
    {
        $entityManager = static::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getManagerForClass('Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake');

        for ($i = 0; $i < 30; ++$i) {
            $fake = FakeFactory::create();
            $fake->setField('');
            $entityManager->persist($fake);
        }
        $entityManager->flush();

        $this
            ->client
            ->request(
                'POST',
                '/fake/paginator/request',
                [
                    'page' => 4,
                    'limit' => 9,
                ]
            );

        $response = json_decode($this
            ->client
            ->getResponse()
            ->getContent(), true);

        $this->assertEquals(4, $response['totalPages']);
        $this->assertEquals(30, $response['totalElements']);
        $this->assertEquals(4, $response['currentPage']);
        $this->assertEquals(3, $response['count']);
    }

    /**
     * Test paginator with request.
     */
    public function testPaginatorAnnotationRequestFilter()
    {
        $entityManager = static::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getManagerForClass('Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake');

        for ($i = 0; $i < 30; ++$i) {
            $fake = FakeFactory::create();
            $fake->setField('');
            $entityManager->persist($fake);
        }
        $entityManager->flush();

        $this
            ->client
            ->request(
                'POST',
                '/fake/paginator/request',
                [
                    'page' => 2,
                    'limit' => 4,
                    'id' => '1%',
                ]
            );

        $response = json_decode($this
            ->client
            ->getResponse()
            ->getContent(), true);

        $this->assertEquals(3, $response['totalPages']);
        $this->assertEquals(11, $response['totalElements']);
        $this->assertEquals(2, $response['currentPage']);
        $this->assertEquals(4, $response['count']);
    }

    /**
     * Test paginator with multiple where.
     */
    public function testPaginatorMultipleWhereAnnotation()
    {
        $fake = FakeFactory::create();
        $fake->setField('test');

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
                '/fake/paginator/multiplewhere/id/2/1/5'
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
     * Test paginator simple.
     */
    public function testPaginatorWithLikeWithGetParameterAnnotation()
    {
        $fake = FakeFactory::create();
        $fake->setField('we are doing a test from paginator');
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
                '/fake/paginator/likewithgetparameter?search=test'
            );

        $this->assertEquals(
            '{"count":1}',
            $this
                ->client
                ->getResponse()
                ->getContent()
        );
    }
}
