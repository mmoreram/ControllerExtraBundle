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
 * Class AnnotationWithEntityReference.
 */
abstract class AnnotationWithEntityReference extends Annotation
{
    /**
     * @var string
     *
     * Entity namespace
     */
    protected $namespace;

    /**
     * @var array
     *
     * Factory
     */
    protected $factory;

    /**
     * @var string
     *
     * Repository
     */
    protected $repository;

    /**
     * return namespace.
     *
     * @return null|string Namespace
     */
    public function getNamespace() : ? string
    {
        return $this->namespace;
    }

    /**
     * Get Factory.
     *
     * @return null|array
     */
    public function getFactory() : ? array
    {
        return $this->factory;
    }

    /**
     * Get Repository.
     *
     * @return null|array
     */
    public function getRepository() : ? array
    {
        return $this->repository;
    }
}
