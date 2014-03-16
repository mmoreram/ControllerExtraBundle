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

namespace Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory;

use Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\FakeEntity;

/**
 * Class FakeFactory
 */
class FakeFactory
{
    /**
     * Returns a new FakeEntity instance
     *
     * @return FakeEntity Fake entity
     */
    public function create()
    {
        return new FakeEntity();
    }

    /**
     * Returns a new FakeEntity instance
     *
     * @return FakeEntity Fake entity
     */
    static public function createStatic()
    {
        return new FakeEntity();
    }
}
 