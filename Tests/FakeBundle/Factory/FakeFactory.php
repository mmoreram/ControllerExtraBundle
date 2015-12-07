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

namespace Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Factory;

use Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake;

/**
 * Class FakeFactory.
 */
class FakeFactory
{
    /**
     * Returns a new Fake instance.
     *
     * @return Fake Fake entity
     */
    public static function create()
    {
        return new Fake();
    }

    /**
     * Returns a new Fake instance.
     *
     * @return Fake Fake entity
     */
    public function createNonStatic()
    {
        return self::create();
    }

    /**
     * Returns a new Fake instance.
     *
     * @return Fake Fake entity
     */
    public static function generate()
    {
        return self::create();
    }

    /**
     * Returns a new Fake instance.
     *
     * @return Fake Fake entity
     */
    public function generateNonStatic()
    {
        return self::create();
    }
}
