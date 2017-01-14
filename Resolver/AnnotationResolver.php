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

namespace Mmoreram\ControllerExtraBundle\Resolver;

use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;

use Mmoreram\ControllerExtraBundle\Annotation\Annotation;

/**
 * Class AnnotationResolver.
 */
abstract class AnnotationResolver
{
    /**
     * Return parameter type.
     *
     * @param ReflectionMethod $method
     * @param string           $parameterName
     * @param string|null      $default
     *
     * @return string|null
     */
    public function getParameterType(
        ReflectionMethod $method,
        string $parameterName,
        ? string $default = null
    ) : ? string {
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

    /**
     * Specific annotation evaluation.
     *
     * This method must be implemented in every single EventListener
     * with specific logic
     *
     * All method code will executed only if specific active flag is true
     *
     * @param Request          $request
     * @param Annotation       $annotation
     * @param ReflectionMethod $method
     */
    abstract public function evaluateAnnotation(
        Request $request,
        Annotation $annotation,
        ReflectionMethod $method
    );
}
