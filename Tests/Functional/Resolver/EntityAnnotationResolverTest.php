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

declare(strict_types=1);

namespace Mmoreram\ControllerExtraBundle\Tests\Functional\Resolver;

use Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory\FakeFactory;
use Mmoreram\ControllerExtraBundle\Tests\Functional\FunctionalTest;

/**
 * Class EntityAnnotationResolverTest.
 */
class EntityAnnotationResolverTest extends FunctionalTest
{
    /**
     * testAnnotation.
     */
    public function testAnnotation()
    {
        $this
            ->client
            ->request(
                'GET',
                '/fake/entity'
            );

        $this->assertEquals(
            '[true]',
            $this
                ->client
                ->getResponse()
                ->getContent()
        );
    }

    /**
     * Test fake mapping.
     */
    public function testMappingAnnotation()
    {
        $fake = FakeFactory::create();
        $fake->setField('');
        $this->save($fake);

        $this
            ->client
            ->request(
                'GET',
                '/fake/entity/mapped/1'
            );

        $this->assertEquals(
            '{"id":1}',
            $this
                ->client
                ->getResponse()
                ->getContent()
        );
    }

    /**
     * Test fake mapping.
     */
    public function testMappingManyAnnotation()
    {
        $this->reloadSchema();
        $fake = FakeFactory::create();
        $fake->setField('value');
        $this->save($fake);

        $this
            ->client
            ->request(
                'GET',
                '/fake/entity/mapped/many/1'
            );

        $this->assertEquals(
            '{"id":1,"other":true}',
            $this
                ->client
                ->getResponse()
                ->getContent()
        );
    }

    /**
     * Test fake mapping.
     */
    public function testMappingManyFailAnnotation()
    {
        $this->reloadSchema();
        $fake = FakeFactory::create();
        $fake->setField('value2');
        $this->save($fake);

        $this
            ->client
            ->request(
                'GET',
                '/fake/entity/mapped/many/1'
            );

        $result = json_decode($this
            ->client
            ->getResponse()
            ->getContent(), true);

        $this->assertEquals(
            'Doctrine\ORM\EntityNotFoundException',
            $result['namespace']
        );
    }

    /**
     * Test entity annotation with mapping fallback.
     */
    public function testMappingFallback()
    {
        $this->reloadSchema();
        $this
            ->client
            ->request(
                'GET',
                '/fake/entity/mapped/fallback/1'
            );

        $this->assertEquals(
            '[true]',
            $this
                ->client
                ->getResponse()
                ->getContent()
        );
    }

    /**
     * Test entity annotation when entity is not found and entity exception is set.
     */
    public function testEntityNotFound()
    {
        $this
            ->client
            ->request(
                'GET',
                '/fake/entity/not/found/not-found-id'
            );

        $result = json_decode($this
            ->client
            ->getResponse()
            ->getContent(), true);

        $this->assertEquals(
            'Doctrine\ORM\EntityNotFoundException',
            $result['namespace']
        );
    }
}
