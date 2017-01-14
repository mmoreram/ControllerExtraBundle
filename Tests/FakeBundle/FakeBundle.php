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

namespace Mmoreram\ControllerExtraBundle\Tests\FakeBundle;

use Mmoreram\BaseBundle\Mapping\MappingBagCollection;
use Mmoreram\BaseBundle\Mapping\MappingBagProvider;
use Mmoreram\BaseBundle\SimpleBaseBundle;

/**
 * Class FakeBundle.
 */
class FakeBundle extends SimpleBaseBundle
{
    /**
     * get config files.
     *
     * @return array
     */
    public function getConfigFiles() : array
    {
        return [
            'services',
        ];
    }

    /**
     * get mapping bag provider.
     *
     * @return MappingBagProvider|null
     */
    public function getMappingBagProvider() : ? MappingBagProvider
    {
        return new class() implements MappingBagProvider {
            /**
             * Get mapping bag collection.
             *
             * @return MappingBagCollection
             */
            public function getMappingBagCollection() : MappingBagCollection
            {
                return MappingBagCollection::create(
                    ['fake' => 'Fake'],
                    '@FakeBundle',
                    'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity'
                );
            }
        };
    }
}
