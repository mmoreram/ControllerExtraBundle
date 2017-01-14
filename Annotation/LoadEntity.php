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
 * Entity annotation driver.
 *
 * @Annotation
 * @Target({"METHOD"})
 */
final class LoadEntity extends AnnotationWithEntityReference
{
    /**
     * @var string
     *
     * Name of the parameter
     */
    protected $name;

    /**
     * @var array
     *
     * Mapping
     */
    protected $mapping = [];

    /**
     * @var bool
     *
     * Mapping fallback
     */
    protected $mappingFallback;

    /**
     * @var array
     *
     * Setters
     */
    protected $setters = [];

    /**
     * @var bool
     *
     * Persist entity
     */
    protected $persist;

    /**
     * return name.
     *
     * @return null|string
     */
    public function getName() : ? string
    {
        return $this->name;
    }

    /**
     * return mapping.
     *
     * @return array
     */
    public function getMapping() : array
    {
        return $this->mapping;
    }

    /**
     * Get MappingFallback.
     *
     * @return null|bool
     */
    public function getMappingFallback() : ? bool
    {
        return $this->mappingFallback;
    }

    /**
     * return setters.
     *
     * @return array
     */
    public function getSetters() : array
    {
        return $this->setters;
    }

    /**
     * return persist.
     *
     * @return null|bool
     */
    public function getPersist() : ? bool
    {
        return $this->persist;
    }
}
