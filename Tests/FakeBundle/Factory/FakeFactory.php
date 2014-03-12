<?php

/**
 * This file is part of BeEcommerce.
 *
 * @author Befactory Team
 * @since  2013
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
 