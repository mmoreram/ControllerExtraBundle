<?php

/**
 * This file is part of the Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 */

namespace Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity;

/**
 * Fake Entity object
 */
class Fake
{
    /**
     * @var integer
     *
     * Id
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer $id Id
     */
    public function getId()
    {
        return $this->id;
    }

}
