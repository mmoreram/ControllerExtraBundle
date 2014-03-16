<?php

/**
 * This file is part of the Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mmoreram\ControllerExtraBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\Request;
use ReflectionMethod;

use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;
use Mmoreram\ControllerExtraBundle\Resolver\Interfaces\AnnotationResolverInterface;

/**
 * Resolver Event Listener
 */
class ResolverEventListener
{

    /**
     * @var KernelInterface
     *
     * Kernel
     */
    protected $kernel;

    /**
     * @var Reader
     *
     * Annotation Reader
     */
    protected $reader;

    /**
     * @var array
     *
     * Resolver stack
     */
    private $resolverStack = array();

    /**
     * Construct method
     *
     * @param KernelInterface $kernel Kernel
     * @param Reader          $reader Reader
     */
    public function __construct(KernelInterface $kernel, Reader $reader)
    {
        $this->kernel = $kernel;
        $this->reader = $reader;
    }

    /**
     * Return kernel object
     *
     * @return KernelInterface Kernel
     */
    protected function getKernel()
    {
        return $this->kernel;
    }

    /**
     * Return reader
     *
     * @return Reader Reader
     */
    protected function getReader()
    {
        return $this->reader;
    }

    /**
     * Return resolver stack
     *
     * @return array Resolver stack
     */
    public function getResolverStack()
    {
        return $this->resolverStack;
    }

    /**
     * Add resolver into stack
     *
     * @param AnnotationResolverInterface $resolver Resolver
     *
     * @return AnnotationEventListener self Object
     */
    public function addResolver(AnnotationResolverInterface $resolver)
    {
        $this->resolverStack[] = $resolver;

        return $this;
    }

    /**
     * Method executed while loading Controller
     *
     * @param FilterControllerEvent $event Filter Controller event
     */
    public function onKernelController(FilterControllerEvent $event)
    {

        /**
         * Data load
         */
        $controller = $event->getController();

        /**
         * If is not a valid controller structure, return
         */
        if (!is_array($controller)) {
            return;
        }

        $request = $event->getRequest();
        $method = new ReflectionMethod($controller[0], $controller[1]);

        /**
         * Given specific configuration, analyze full request
         */
        $this->analyzeRequest($request, $this->getReader(), $method);
    }

    /**
     * Evaluate request
     *
     * @param Request          $request Request
     * @param Reader           $reader  Reader
     * @param ReflectionMethod $method  Method
     */
    public function analyzeRequest(Request $request, Reader $reader, ReflectionMethod $method)
    {
        /**
         * Annotations load
         */
        $methodAnnotations = $reader->getMethodAnnotations($method);

        /**
         * Every annotation found is parsed
         */
        foreach ($methodAnnotations as $annotation) {

            if ($annotation instanceof Annotation) {

                $this->analyzeAnnotation($request, $method, $annotation, $this->resolverStack);
            }
        }
    }

    /**
     * Allow every available resolver to solve its own logic
     *
     * @param Request          $request       Request
     * @param ReflectionMethod $method        Method
     * @param Annotation       $annotation    Annotation
     * @param array            $resolverStack Resolver stack
     */
    public function analyzeAnnotation(Request $request, ReflectionMethod $method, Annotation $annotation, array $resolverStack)
    {

        /**
         * Every resolver must evaluate its logic
         */
        foreach ($resolverStack as $resolver) {

            $resolver->evaluateAnnotation($request, $annotation, $method);
        }
    }
}
