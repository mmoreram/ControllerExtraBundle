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

namespace Mmoreram\ControllerExtraBundle\Resolver\Abstracts;

use ReflectionMethod;
use ReflectionParameter;

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
     *
     * @return string Parameter type
     */
    public function getParameterType(ReflectionMethod $method, $parameterName)
    {
        /**
         * Method parameters load.
         *
         * A hash is created to access to all needed parameters
         * with cost O(1)
         */
        $parameters = $method->getParameters();
        $parametersIndexed = array();

        foreach ($parameters as $parameter) {

            $parametersIndexed[$parameter->getName()] = $parameter;
        }

        /**
         * Get parameter class for TypeHinting
         *
         * @var ReflectionParameter $parameter
         */
        $parameter = $parametersIndexed[$parameterName];

        return $parameter
            ->getClass()
            ->getName();
    }
}
