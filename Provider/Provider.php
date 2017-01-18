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

namespace Mmoreram\ControllerExtraBundle\Provider;

/**
 * Interface Provider.
 */
interface Provider
{
    /**
     * Provide related value given reference. If not found, return the same
     * reference, treated as a value.
     *
     * A map array is optional in order to have a normalization guide.
     *
     * @param string $reference
     * @param array  $map
     *
     * @return mixed
     */
    public function provide(
        string $reference,
        array $map = []
    );
}
