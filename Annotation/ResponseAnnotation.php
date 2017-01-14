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

namespace Mmoreram\ControllerExtraBundle\Annotation;

/**
 * Class ResponseAnnotation.
 */
abstract class ResponseAnnotation extends Annotation
{
    /**
     * @var int
     *
     * Status
     */
    protected $status = 200;

    /**
     * @var array
     *
     * Headers
     */
    protected $headers = [];

    /**
     * Get response status.
     *
     * @return int
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * Get response headers.
     *
     * @return array
     */
    public function getHeaders() : array
    {
        return $this->headers;
    }
}
