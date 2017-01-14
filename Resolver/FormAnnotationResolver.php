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

use ReflectionMethod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormRegistryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

use Mmoreram\ControllerExtraBundle\Annotation\Annotation;
use Mmoreram\ControllerExtraBundle\Annotation\CreateForm;

/**
 * Class FormAnnotationResolver.
 */
class FormAnnotationResolver extends AnnotationResolver
{
    /**
     * @var FormRegistryInterface
     *
     * FormRegistry
     */
    private $formRegistry;

    /**
     * @var FormRegistryInterface
     *
     * FormRegistry
     */
    private $formFactory;

    /**
     * @var string
     *
     * Default field name
     */
    private $defaultName;

    /**
     * Construct method.
     *
     * @param FormRegistryInterface $formRegistry
     * @param FormFactoryInterface  $formFactory
     * @param string                $defaultName
     */
    public function __construct(
        FormRegistryInterface $formRegistry,
        FormFactoryInterface $formFactory,
        string $defaultName
    ) {
        $this->formRegistry = $formRegistry;
        $this->formFactory = $formFactory;
        $this->defaultName = $defaultName;
    }

    /**
     * Specific annotation evaluation.
     *
     * @param Request          $request
     * @param Annotation       $annotation
     * @param ReflectionMethod $method
     */
    public function evaluateAnnotation(
        Request $request,
        Annotation $annotation,
        ReflectionMethod $method
    ) {
        /**
         * Annotation is only laoded if is typeof WorkAnnotation.
         */
        if ($annotation instanceof CreateForm) {

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
                FormInterface::class
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
    }

    /**
     * Built and return desired object.
     *
     * @param Request              $request
     * @param FormFactoryInterface $formFactory
     * @param CreateForm           $annotation
     * @param string               $parameterClass
     * @param AbstractType         $type
     *
     * @return mixed
     */
    private function getBuiltObject(
        Request $request,
        FormFactoryInterface $formFactory,
        CreateForm $annotation,
        string $parameterClass,
        AbstractType $type
    ) {
        /**
         * Checks if parameter typehinting is AbstractType
         * In this case, form type as defined method parameter.
         */
        if (AbstractType::class == $parameterClass) {
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
                Form::class,
                FormInterface::class,
            ]
        )) {
            return $form;
        }

        /**
         * Checks if parameter typehinting is FormView
         * In this case, inject form's view as defined method parameter.
         */
        if (FormView::class == $parameterClass) {
            return $form->createView();
        }
    }
}
