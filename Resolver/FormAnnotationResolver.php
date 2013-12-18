<?php

/**
 * Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 */

namespace Mmoreram\ControllerExtraBundle\Resolver;

use Symfony\Component\Form\FormRegistryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;

use Mmoreram\ControllerExtraBundle\Resolver\Interfaces\AnnotationResolverInterface;
use Mmoreram\ControllerExtraBundle\Annotation\Form as AnnotationForm;
use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;


/**
 * FormAnnotationResolver, an implementation of  AnnotationResolverInterface
 */
class FormAnnotationResolver implements AnnotationResolverInterface
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
     * @param FormRegistryInterface $formRegistry Form Registry
     * @param FormFactoryInterface  $formFactory  Form Factory
     */
    public function __construct(FormRegistryInterface $formRegistry, FormFactoryInterface $formFactory)
    {
        $this->formRegistry = $formRegistry;
        $this->formFactory = $formFactory;
    }


    /**
     * Specific annotation evaluation.
     *
     * @param array      $controller        Controller
     * @param Request    $request           Request
     * @param Annotation $annotation        Annotation
     * @param array      $parametersIndexed Parameters indexed
     *
     * @return AbstractEventListener self Object
     */
    public function evaluateAnnotation(array $controller, Request $request, Annotation $annotation, array $parametersIndexed)
    {

        /**
         * Annotation is only laoded if is typeof WorkAnnotation
         */
        if ($annotation instanceof AnnotationForm) {

            /**
             * Once loaded Annotation info, we just instanced Service name
             */
            $annotationValue = !is_null($annotation->getName())
                             ? $annotation->getName()
                             : 'form';

            /**
             * Get FormType object given a service name
             */
            $type   = class_exists($annotationValue)
                    ? new $annotationValue
                    : $this
                        ->formRegistry
                        ->getType($annotationValue)
                        ->getInnerType();

            /**
             * Get parameter class for TypeHinting
             */
            $parameterClass = $parametersIndexed[$annotation->variable]
                ->getClass()
                ->getName();

            /**
             * Requiring result with calling getBuiltObject(), set as request attribute desired element
             */
            $request->attributes->set(
                $annotation->getVariable(),
                $this->getBuiltObject($request, $this->formFactory, $annotation, $parameterClass, $type)
            );
        }
    }


    /**
     * Built desired object.
     *
     * @param Request              $request        Request
     * @param FormFactoryInterface $formFactory    Form Factory
     * @param Annotation           $annotation     Annotation
     * @param string               $parameterClass Class type of  method parameter
     * @param AbstractType         $type           Built Type object
     *
     * @return Mixed object to inject as a method parameter
     */
    private function getBuiltObject(Request $request, FormFactoryInterface $formFactory, Annotation $annotation, $parameterClass, AbstractType $type)
    {
        /**
         * Checks if parameter typehinting is AbstractType
         * In this case, form type as defined method parameter
         */
        if ('Symfony\\Component\\Form\\AbstractType' == $parameterClass) {

            return $type;
        }

        $entity = $request->attributes->get($annotation->getEntity());

        /**
         * Creates form object from type
         */
        $form = $formFactory->create($type, $entity);

        /**
         * Handling request if needed
         */
        if ($annotation->getHandleRequest()) {

            $form->handleRequest($request);
        }

        /**
         * Checks if parameter typehinting is Form
         * In this case, inject form as defined method parameter
         */
        if ('Symfony\\Component\\Form\\Form' == $parameterClass) {

            return $form;
        }

        /**
         * Checks if parameter typehinting is FormView
         * In this case, inject form's view as defined method parameter
         */
        if ('Symfony\\Component\\Form\\FormView' == $parameterClass) {

            return $form->createView();
        }
    }
}
