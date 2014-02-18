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

namespace Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Controller;

use Mmoreram\ControllerExtraBundle\Annotation\Entity;
use Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\FakeEntity;

/**
 * Fake Controller object
 */
class FakeController
{

    /**
     * Public method
     *
     * @Entity(
     *      class = "FakeBundle:FakeEntity",
     *      name = "entityName"
     * )
     * @Entity(
     *      class = "FakeBundle:FakeEntity"
     * )
     */
    public function entityMethod()
    {

    }
}
