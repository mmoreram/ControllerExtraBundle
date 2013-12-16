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
use Symfony\Component\Form\FormRegistryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\Request;

use Mmoreram\ControllerExtraBundle\EventListener\Abstracts\AbstractEventListener;
use Mmoreram\ControllerExtraBundle\Annotation\Form as AnnotationForm;
use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;


/**
 * FormAnnotationEventListener, an extension of AbstractEventListener
 */
class FormAnnotationEventListener extends AbstractEventListener
{

    /**
     * @var FormRegistryInterface
     *
     * FormRegistry
     */
    protected $formRegistry;


    /**
     * @var FormRegistryInterface
     *
     * FormRegistry
     */
    protected $formFactory;


    /**
     * Construct method
     *
     * @param KernelInterface       $kernel       Kernel
     * @param Reader                $reader       Reader
     * @param FormRegistryInterface $formRegistry Form Registry
     * @param FormFactoryInterface  $formFactory  Form Factory
     */
    public function __construct(KernelInterface $kernel, Reader $reader, FormRegistryInterface $formRegistry, FormFactoryInterface $formFactory)
    {
        parent::__construct($kernel, $reader);

        $this->formRegistry = $formRegistry;
        $this->formFactory = $formFactory;
    }


    /**
     * Specific annotation evaluation.
     *
     * @param array $controller Controller
     * @param Request $request Request
     * @param Annotation $annotation Annotation
     *
     * @return AbstractEventListener self Object
     */
    public function evaluateAnnotation(array $controller, Request $request, Annotation $annotation)
    {

        /**
         * Annotation is only laoded if is typeof WorkAnnotation
         */
        if ($annotation instanceof AnnotationForm) {

            /**
             * Once loaded Annotation info, we just instanced Service name
             */
            $annotationValue = $annotation->value;

            /**
             * Get FormType object given a service name
             */
            $type   = class_exists($annotationValue)
                    ? new $annotationValue
                    : $this
                        ->formRegistry
                        ->getType($annotationValue)
                        ->getInnerType();

            $currentParameter = $this->parametersIndexed[$annotation->variable]
                ->getClass()
                ->getName();

            /**
             * Checks if parameter typehinting is AbstractType
             * In this case, form type as defined method parameter
             */
            if ('Symfony\\Component\\Form\\AbstractType' == $currentParameter) {

                $request->attributes->set($annotation->variable, $type);

                return;
            }

            $entity = $request->attributes->get($annotation->entity);

            /**
             * Creates form object from type
             */
            $form = $this
                ->formFactory
                ->create($type, $entity);

            /**
             * Checks if parameter typehinting is Form
             * In this case, inject form as defined method parameter
             */
            if ('Symfony\\Component\\Form\\Form' == $currentParameter) {

                $request->attributes->set($annotation->variable, $form);

                return;
            }

            /**
             * Handling request if needed
             */
            if ($annotation->handleRequest) {

                $form->handleRequest($request);
            }

            /**
             * Checks if parameter typehinting is FormView
             * In this case, inject form's view as defined method parameter
             */
            if ('Symfony\\Component\\Form\\FormView' == $currentParameter) {

                $request->attributes->set($annotation->variable, $form->createView());

                return;
            }
        }
    }
}
