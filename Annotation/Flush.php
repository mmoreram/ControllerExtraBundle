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
 * Flush annotation driver.
 *
 * @Annotation
 * @Target({"METHOD"})
 */
final class Flush extends Annotation
{
    /**
     * @var string
     *
     * Manager to use when flushing
     */
    protected $manager;

    /**
     * @var string
     *
     * Entity from Request ParameterBag to flush
     */
    protected $entity;

    /**
     * return manager.
     *
     * @return null|string
     */
    public function getManager() : ? string
    {
        return $this->manager;
    }

    /**
     * return entity.
     *
     * @return null|string
     */
    public function getEntity() : ? string
    {
        return $this->entity;
    }
}
