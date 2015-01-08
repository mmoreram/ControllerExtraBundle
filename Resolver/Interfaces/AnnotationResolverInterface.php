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

namespace Mmoreram\ControllerExtraBundle\Resolver\Interfaces;

use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;

use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;

/**
 * Abstract Annotation resolver
 */
interface AnnotationResolverInterface
{

    /**
     * Specific annotation evaluation.
     *
     * This method must be implemented in every single EventListener
     * with specific logic
     *
     * All method code will executed only if specific active flag is true
     *
     * @param Request          $request    Request
     * @param Annotation       $annotation Annotation
     * @param ReflectionMethod $method     Method
     *
     * @return AnnotationResolverInterface self Object
     */
    public function evaluateAnnotation(
        Request $request,
        Annotation $annotation,
        ReflectionMethod $method
    );
}
