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
            '{"dql":"SELECT x, r4, r5 FROM Mmoreram\\\\ControllerExtraBundle\\\\Tests\\\\FakeBundle\\\\Entity\\\\Fake x INNER JOIN x.relation3 r3 INNER JOIN x.relation4 r4 LEFT JOIN x.relation r LEFT JOIN x.relation2 r2 LEFT JOIN x.relation5 r5 WHERE enabled = ?where0 AND x.address1 IS NOT NULL AND x.address2 IS NOT NULL ORDER BY createdAt ASC, id ASC"}',
            $this
                ->client
                ->getResponse()
                ->getContent()
        );
    }
}
