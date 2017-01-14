<?php

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
