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
     * @return Fake
     */
    public static function create()
    {
        $fake = new Fake();
        $fake->setField('s_c');

        return $fake;
    }

    /**
     * Returns a new Fake instance.
     *
     * @return Fake
     */
    public function createNonStatic()
    {
        $fake = self::create();
        $fake->setField('ns_c');

        return $fake;
    }

    /**
     * Returns a new Fake instance.
     *
     * @return Fake
     */
    public static function generate()
    {
        $fake = self::create();
        $fake->setField('s_g');

        return $fake;
    }

    /**
     * Returns a new Fake instance.
     *
     * @return Fake
     */
    public function generateNonStatic()
    {
        $fake = self::create();
        $fake->setField('ns_g');

        return $fake;
    }
}
