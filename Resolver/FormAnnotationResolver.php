<?php

/**
 * This file is part of the Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 */

namespace Mmoreram\ControllerExtraBundle\Resolver;

use Symfony\Component\Form\FormRegistryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;
use ReflectionMethod;
use ReflectionParameter;

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
     * @var string
     *
     * Default field name
     */
    protected $defaultName;

    /**
     * Construct method
     *
     * @param FormRegistryInterface $formRegistry Form Registry
     * @param FormFactoryInterface  $formFactory  Form Factory
     * @param string                $defaultName  Default name
     */
    public function __construct(
        FormRegistryInterface $formRegistry,
        FormFactoryInterface $formFactory,
        $defaultName
    )
    {
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
    )
    {
        /**
         * Annotation is only laoded if is typeof WorkAnnotation
         */
        if ($annotation instanceof AnnotationForm) {

            /**
             * Once loaded Annotation info, we just instanced Service name
             */
            $annotationValue = $annotation->getClass();

            /**
             * Get FormType object given a service name
             */
            $type = class_exists($annotationValue)
                ? new $annotationValue
                : $this
                    ->formRegistry
                    ->getType($annotationValue)
                    ->getInnerType();

            /**
             * Get the parameter name. If not defined, is set as $form
             */
            $parameterName = $annotation->getName() ? : $this->defaultName;

            /**
             * Method parameters load.
             *
             * A hash is created to access to all needed parameters
             * with cost O(1)
             */
            $parameters = $method->getParameters();
            $parametersIndexed = array();

            foreach ($parameters as $parameter) {

                $parametersIndexed[$parameter->getName()] = $parameter;
            }

            /**
             * Get parameter class for TypeHinting
             *
             * @var ReflectionParameter $parameter
             */
            $parameter = $parametersIndexed[$parameterName];
            $parameterClass = $parameter
                ->getClass()
                ->getName();

            /**
             * Requiring result with calling getBuiltObject(), set as request
             * attribute desired element
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
     * @return Mixed object to inject as a method parameter
     */
    protected function getBuiltObject(
        Request $request,
        FormFactoryInterface $formFactory,
        AnnotationForm $annotation,
        $parameterClass,
        AbstractType $type
    )
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

            if ($annotation->getValidate()) {
                $request->attributes->set(
                    $annotation->getValidate(),
                    $form->isValid()
                );
            }
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
