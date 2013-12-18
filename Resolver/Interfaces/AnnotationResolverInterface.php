<?php

/**
 * Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 */

namespace Mmoreram\ControllerExtraBundle\Resolver\Interfaces;

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
     * This method must be implemented in every single EventListener with specific logic
     *
     * All method code will executed only if specific active flag is true
     *
     * @param array      $controller        Controller
     * @param Request    $request           Request
     * @param Annotation $annotation        Annotation
     * @param array      $parametersIndexed Parameters indexed
     *
     * @return AbstractEventListener self Object
     */
    public function evaluateAnnotation(array $controller, Request $request, Annotation $annotation, array $parametersIndexed);
}
