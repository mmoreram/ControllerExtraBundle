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