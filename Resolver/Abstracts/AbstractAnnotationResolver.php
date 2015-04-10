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

namespace Mmoreram\ControllerExtraBundle\Resolver\Abstracts;

use ReflectionMethod;

/**
 * Class AbstractAnnotationResolver
 */
class AbstractAnnotationResolver
{
    /**
     * Return parameter type
     *
     * @param ReflectionMethod $method        Method
     * @param string           $parameterName Parameter name
     * @param string|null      $default       Default type if not defined
     *
     * @return string|null Parameter type
     */
    public function getParameterType(ReflectionMethod $method, $parameterName, $default = null)
    {
        $parameters = $method->getParameters();

        foreach ($parameters as $parameter) {
            if ($parameter->getName() === $parameterName) {
                $class = $parameter->getClass();

                return $class
                    ? $class->getName()
                    : $default;
            }
        }

        return $default;
    }
}
