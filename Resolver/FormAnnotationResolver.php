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

namespace Mmoreram\ControllerExtraBundle\Resolver;

use ReflectionMethod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormRegistryInterface;
use Symfony\Component\HttpFoundation\Request;

use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;
use Mmoreram\ControllerExtraBundle\Annotation\Form as AnnotationForm;
use Mmoreram\ControllerExtraBundle\Resolver\Abstracts\AbstractAnnotationResolver;
use Mmoreram\ControllerExtraBundle\Resolver\Interfaces\AnnotationResolverInterface;

/**
 * FormAnnotationResolver, an implementation of  AnnotationResolverInterface.
 */
class FormAnnotationResolver extends AbstractAnnotationResolver implements AnnotationResolverInterface
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
     * @var string
     *
     * Default field name
     */
    protected $defaultName;

    /**
     * Construct method.
     *
     * @param FormRegistryInterface $formRegistry Form Registry
     * @param FormFactoryInterface  $formFactory  Form Factory
     * @param string                $defaultName  Default name
     */
    public function __construct(
        FormRegistryInterface $formRegistry,
        FormFactoryInterface $formFactory,
        $defaultName
    ) {
        $this->formRegistry = $formRegistry;
        $this->formFactory = $formFactory;
        $this->defaultName = $defaultName;
    }

    /**
     * Specific annotation evaluation.
     *
     * @param Request          $request    Request
     * @param Annotation       $annotation Annotation
     * @param ReflectionMethod $method     Method
     *
     * @return FormAnnotationResolver self Object
     */
    public function evaluateAnnotation(
        Request $request,
        Annotation $annotation,
        ReflectionMethod $method
    ) {
        /**
         * Annotation is only laoded if is typeof WorkAnnotation.
         */
        if ($annotation instanceof AnnotationForm) {

            /**
             * Once loaded Annotation info, we just instanced Service name.
             */
            $annotationValue = $annotation->getClass();

            /**
             * Get FormType object given a service name.
             */
            $type = $this
                    ->formRegistry
                    ->getType($annotationValue)
                    ->getInnerType();

            /**
             * Get the parameter name. If not defined, is set as $form.
             */
            $parameterName = $annotation->getName() ?: $this->defaultName;
            $parameterClass = $this->getParameterType(
                $method,
                $parameterName,
                'Symfony\\Component\\Form\\FormInterface'
            );

            /**
             * Requiring result with calling getBuiltObject(), set as request
             * attribute desired element.
             */
            $request->attributes->set(
                $parameterName,
                $this->getBuiltObject(
                    $request,
                    $this->formFactory,
                    $annotation,
                    $parameterClass,
                    $type
                )
            );
        }

        return $this;
    }

    /**
     * Built desired object.
     *
     * @param Request              $request        Request
     * @param FormFactoryInterface $formFactory    Form Factory
     * @param AnnotationForm       $annotation     Annotation
     * @param string               $parameterClass Class type of  method parameter
     * @param AbstractType         $type           Built Type object
     *
     * @return mixed object to inject as a method parameter
     */
    protected function getBuiltObject(
        Request $request,
        FormFactoryInterface $formFactory,
        AnnotationForm $annotation,
        $parameterClass,
        AbstractType $type
    ) {
        /**
         * Checks if parameter typehinting is AbstractType
         * In this case, form type as defined method parameter.
         */
        if ('Symfony\\Component\\Form\\AbstractType' == $parameterClass) {
            return $type;
        }

        $entity = $request->attributes->get($annotation->getEntity());

        /**
         * Creates form object from type.
         */
        $form = $formFactory->create(get_class($type), $entity);

        /**
         * Handling request if needed.
         */
        if ($annotation->getHandleRequest()) {
            $form->handleRequest($request);

            if ($annotation->getValidate()) {
                $request->attributes->set(
                    $annotation->getValidate(),
                    $form->isValid()
                );
            }
        }

        /**
         * Checks if parameter typehinting is Form
         * In this case, inject form as defined method parameter.
         */
        if (in_array(
            $parameterClass, [
                'Symfony\\Component\\Form\\Form',
                'Symfony\\Component\\Form\\FormInterface',
            ]
        )) {
            return $form;
        }

        /**
         * Checks if parameter typehinting is FormView
         * In this case, inject form's view as defined method parameter.
         */
        if ('Symfony\\Component\\Form\\FormView' == $parameterClass) {
            return $form->createView();
        }
    }
}
