<?php

/**
 * Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 */

namespace Mmoreram\ControllerExtraBundle\Resolver\Abstracts;

use Symfony\Component\HttpFoundation\Request;

use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;


/**
 * Abstract Annotation resolver
 */
abstract class AbstractAnnotationResolver
{

    /**
     * @var boolean
     *
     * Current annotation must be evaluated
     */
    protected $active;


    /**
     * Specific annotation evaluation.
     *
     * This method must be implemented in every single EventListener with specific logic
     *
     * @param boolean $active Define if current annotation must be evaluated
     *
     * @return AbstractEventListener self Object
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }


    /**
     * Return active value
     *
     * @return boolean Current annotation parsing is active
     */
    public function isActive()
    {
        return $this->active;
    }


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
    abstract public function evaluateAnnotation(array $controller, Request $request, Annotation $annotation, array $parametersIndexed);
}
