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

use Mmoreram\ControllerExtraBundle\Tests\Functional\FunctionalTest;

/**
 * Class FormAnnotationResolverTest.
 */
class FormAnnotationResolverTest extends FunctionalTest
{
    /**
     * testAnnotation.
     */
    public function testAnnotation()
    {
        $this
            ->client
            ->request('GET', '/fake/form');

        $this->assertEquals(
            '[true]',
            $this
                ->client
                ->getResponse()
                ->getContent()
        );
    }
}
