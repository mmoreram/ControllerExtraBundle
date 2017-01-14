<?php
/**
 * File header placeholder.
 */

namespace Mmoreram\ControllerExtraBundle\Provider;

/**
 * Class ProviderCollector.
 */
class ProviderCollector implements Provider
{
    /**
     * @var Provider[]
     *
     * Providers
     */
    private $providers = [];

    /**
     * Add a provider.
     *
     * @param Provider $provider
     */
    public function addProvider(Provider $provider)
    {
        $this->providers[] = $provider;
    }

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
    ) {
        foreach ($this->providers as $provider) {
            $value = $provider->provide(
                $reference,
                $map
            );

            if ($value != $reference) {
                return $value;
            }
        }

        return $reference;
    }
}
