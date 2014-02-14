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

namespace Mmoreram\ControllerExtraBundle\Resolver;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use ReflectionMethod;

use Mmoreram\ControllerExtraBundle\Resolver\Interfaces\AnnotationResolverInterface;
use Mmoreram\ControllerExtraBundle\Annotation\Entity as AnnotationEntity;
use Mmoreram\ControllerExtraBundle\Exceptions\EntityNotFoundException;
use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;

/**
 * EntityAnnotationResolver, an implementation of AnnotationResolverInterface
 */
class EntityAnnotationResolver implements AnnotationResolverInterface
{

    /**
     * @var array
     *
     * Kernel bundles list
     */
    protected $kernelBundles;

    /**
     * @var string
     *
     * Default field name
     */
    protected $defaultName;

    /**
     * Construct method
     *
     * @param array  $kernelBundles Kernel bundles list
     * @param string $defaultName   Default name
     */
    public function __construct(array $kernelBundles, $defaultName)
    {
        $this->kernelBundles = $kernelBundles;
        $this->defaultName = $defaultName;
    }

    /**
     * Specific annotation evaluation.
     *
     * @param Request          $request    Request
     * @param Annotation       $annotation Annotation
     * @param ReflectionMethod $method     Method
     */
    public function evaluateAnnotation(Request $request, Annotation $annotation, ReflectionMethod $method)
    {

        /**
         * Annotation is only laoded if is typeof AnnotationEntity
         */
        if ($annotation instanceof AnnotationEntity) {

            $namespace = explode(':', $annotation->getClass(), 2);

            /**
             * If entity definition is wrong, throw exception
             * If bundle not exists or is not actived, throw Exception
             */
            if (
                    !isset($namespace[0]) ||
                    !isset($this->kernelBundles[$namespace[0]])||
                    !isset($namespace[1])) {

                throw new EntityNotFoundException;
            }

            $bundle = $this->kernelBundles[$namespace[0]];
            $bundleNamespace = $bundle->getNamespace();
            $entityNamespace = $bundleNamespace . '\\Entity\\' . $namespace[1];

            if (!class_exists($entityNamespace)) {

                throw new EntityNotFoundException;
            }

            /**
             * Creating new instance of desired entity
             */
            $entity = new $entityNamespace();

            $this->evaluateSetters(
                $request->attributes,
                $entity,
                $annotation->getSetters()
            );

            /**
             * Get the parameter name. If not defined, is set as defined in parameters
             */
            $parameterName = $annotation->getName() ?: $this->defaultName;

            $request->attributes->set(
                $parameterName,
                $entity
            );
        }
    }

    /**
     * Evaluate setters
     *
     * @param ParameterBag $attributes Request attributes
     * @param Object       $entity     Entity
     * @param array        $setters    Array of setters
     *
     * @return EntityAnnotationResolver self Object
     */
    public function evaluateSetters(ParameterBag $attributes, $entity, array $setters)
    {
        foreach ($setters as $method => $value) {

            $entity->$method($attributes->get($value));
        }

        return $this;
    }
}
