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

namespace Mmoreram\ControllerExtraBundle\Tests\Functional\Resolver;

use Mmoreram\ControllerExtraBundle\Tests\Functional\AbstractWebTestCase;

/**
 * Class EntityAnnotationResolverTest
 */
class EntityAnnotationResolverTest extends AbstractWebTestCase
{
    /**
     * testAnnotation
     */
    public function testAnnotation()
    {
        $this
            ->client
            ->request(
                'GET',
                '/fake/entity'
            );
    }
}
