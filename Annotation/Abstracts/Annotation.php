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

namespace Mmoreram\ControllerExtraBundle\Annotation\Abstracts;

use Doctrine\Common\Annotations\Annotation as DoctrineAnnotation;

/**
 * Flush annotation driver
 *
 * @Annotation
 */
abstract class Annotation extends DoctrineAnnotation
{

    /**
     * return value
     *
     * @return string Value
     */
    public function getValue()
    {
        return $this->value;
    }
}
