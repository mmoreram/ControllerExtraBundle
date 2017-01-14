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

use Doctrine\Common\Annotations\Reader;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelInterface;

use Mmoreram\ControllerExtraBundle\Annotation\Annotation;

/**
 * Class AnnotationResolverCollector.
 */
final class AnnotationResolverCollector
{
    /**
     * @var KernelInterface
     *
     * Kernel
     */
    private $kernel;

    /**
     * @var Reader
     *
     * Annotation Reader
     */
    private $reader;

    /**
     * @var array
     *
     * Resolver stack
     */
    private $resolverStack = [];

    /**
     * Construct method.
     *
     * @param KernelInterface $kernel
     * @param Reader          $reader
     */
    public function __construct(
        KernelInterface $kernel,
        Reader $reader
    ) {
        $this->kernel = $kernel;
        $this->reader = $reader;
    }

    /**
     * Return resolver stack.
     *
     * @return array
     */
    public function getResolverStack() : array
    {
        return $this->resolverStack;
    }

    /**
     * Add resolver into stack.
     *
     * @param AnnotationResolver $resolver
     */
    public function addResolver(AnnotationResolver $resolver)
    {
        $this->resolverStack[] = $resolver;
    }

    /**
     * Method executed while loading Controller.
     *
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        /**
         * Data load.
         */
        $controller = $event->getController();

        /**
         * If is not a valid controller structure, return.
         */
        if (!is_array($controller)) {
            return;
        }

        $request = $event->getRequest();
        $method = new ReflectionMethod($controller[0], $controller[1]);

        /**
         * Given specific configuration, analyze full request.
         */
        $this->analyzeRequest($request, $this->reader, $method);
    }

    /**
     * Evaluate request.
     *
     * @param Request          $request
     * @param Reader           $reader
     * @param ReflectionMethod $method
     */
    public function analyzeRequest(
        Request $request,
        Reader $reader,
        ReflectionMethod $method
    ) {
        /**
         * Annotations load.
         */
        $methodAnnotations = $reader->getMethodAnnotations($method);

        /**
         * Every annotation found is parsed.
         */
        foreach ($methodAnnotations as $annotation) {
            if ($annotation instanceof Annotation) {
                $this->analyzeAnnotation(
                    $request,
                    $method,
                    $annotation,
                    $this->resolverStack
                );
            }
        }
    }

    /**
     * Allow every available resolver to solve its own logic.
     *
     * @param Request          $request
     * @param ReflectionMethod $method
     * @param Annotation       $annotation
     * @param array            $resolverStack
     */
    public function analyzeAnnotation(
        Request $request,
        ReflectionMethod $method,
        Annotation $annotation,
        array $resolverStack
    ) {

        /**
         * Every resolver must evaluate its logic.
         */
        foreach ($resolverStack as $resolver) {
            $resolver->evaluateAnnotation($request, $annotation, $method);
        }
    }
}
