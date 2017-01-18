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

namespace Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Repository;

use Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake;

/**
 * Class AlternativeRepository.
 */
class AlternativeRepository
{
    /**
     * Find me one, please.
     */
    public function findMeOnePlease(array $parameters) : Fake
    {
        $entity = new Fake();
        $entity->setField('alt-' . $parameters['id']);

        return $entity;
    }

    /**
     * Find me one, please.
     */
    public function findOneBy(array $parameters) : Fake
    {
        $entity = new Fake();
        $entity->setField('alt-fob-' . $parameters['id']);

        return $entity;
    }
}
