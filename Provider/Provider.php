<?php
/**
 * File header placeholder.
 */

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
