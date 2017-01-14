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
 * Class RequestBagAnnotation.
 */
abstract class RequestBagAnnotation extends Annotation
{
    /**
     * @var string
     *
     * The parameter path
     */
    protected $path;

    /**
     * @var string
     *
     * The name that the parameter will get
     */
    protected $name;

    /**
     * @var mixed
     *
     * The default value if the parameter is not set
     */
    protected $default;

    /**
     * Gets the path.
     *
     * @return null|string
     */
    public function getPath() : ? string
    {
        return $this->path;
    }

    /**
     * Gets the name.
     *
     * @return null|string
     */
    public function getName() : ? string
    {
        return $this->name;
    }

    /**
     * Gets the default.
     *
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }
}
