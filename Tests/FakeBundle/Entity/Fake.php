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

namespace Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity;

/**
 * Fake Entity object.
 */
class Fake
{
    /**
     * @var int
     *
     * Id
     */
    private $id;

    /**
     * @var string
     *
     * Field
     */
    private $field;

    /**
     * Get id.
     *
     * @return null|int
     */
    public function getId() : ? int
    {
        return $this->id;
    }

    /**
     * Sets Field.
     *
     * @param null|string $field
     */
    public function setField(? string $field)
    {
        $this->field = $field;
    }

    /**
     * Get Field.
     *
     * @return null|string
     */
    public function getField() : ? string
    {
        return $this->field;
    }
}
