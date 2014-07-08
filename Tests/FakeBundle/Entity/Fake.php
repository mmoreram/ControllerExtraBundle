<?php

/**
 * This file is part of the ControllerExtraBundle for Symfony2.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
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
     * @var string
     *
     * Field
     */
    protected $field;

    /**
     * Get id
     *
     * @return integer $id Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets Field
     *
     * @param string $field Field
     *
     * @return Fake Self object
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get Field
     *
     * @return string Field
     */
    public function getField()
    {
        return $this->field;
    }
}
