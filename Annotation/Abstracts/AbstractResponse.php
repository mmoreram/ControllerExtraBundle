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

/**
 * Class AbstractResponse
 */
abstract class AbstractResponse extends Annotation
{
    /**
     * @var integer
     *
     * Status
     */
    protected $status = 200;

    /**
     * @var array
     *
     * Headers
     */
    protected $headers = array();

    /**
     * Get response status
     *
     * @return integer Response status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get response headers
     *
     * @return integer Response headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
