<?php

/**
 * Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 */

namespace Mmoreram\ControllerExtraBundle\EventListener;

use ReflectionMethod;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\Request;

use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;
use Mmoreram\ControllerExtraBundle\Resolver\Abstracts\AbstractAnnotationResolver;


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
     * Add resolver into stack
     *
     * @param AbstractAnnotationResolver $resolver Resolver
     *
     * @return AnnotationEventListener self Object
     */
    public function addResolver(AbstractAnnotationResolver $resolver)
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
         * Method parameteres load.
         * A hash is created to access to all needed parameters with cost O(1)
         */
        $parameters = $method->getParameters();
        $parametersIndexed = array();

        foreach ($parameters as $parameter) {

            $parametersIndexed[$parameter->getName()] = $parameter;
        }

        /**
         * Given specific configuration, analyze full request
         */
        $this->analyzeRequest($request, $this->getReader(), $controller, $method, $parametersIndexed);
    }


    /**
     * Evaluate request
     *
     * @param Request          $request           Request
     * @param Reader           $reader            Reader
     * @param array            $controller        Controller
     * @param ReflectionMethod $method            Method
     * @param array            $parametersIndexed Parameters indexed
     */
    public function analyzeRequest(Request $request, Reader $reader, array $controller, \ReflectionMethod $method, array $parametersIndexed)
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

                $this->analyzeAnnotation($request, $controller, $parametersIndexed, $annotation, $this->resolverStack);
            }
        }
    }


    /**
     * Allow every available resolver to solve its own logic
     *
     * @param Request    $request           Request
     * @param array      $controller        Controller
     * @param array      $parametersIndexed Parameters indexed
     * @param Annotation $annotation        Annotation
     * @param array      $resolverStack     Resolver stack
     */
    public function analyzeAnnotation(Request $request, array $controller, array $parametersIndexed, Annotation $annotation, array $resolverStack)
    {

        /**
         * Every resolver must evaluate its logic
         */
        foreach ($resolverStack as $resolver) {

            $resolver->evaluateAnnotation($controller, $request, $annotation, $parametersIndexed);
        }
    }
}
