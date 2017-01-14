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

namespace Mmoreram\ControllerExtraBundle;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Mmoreram\ControllerExtraBundle\CompilerPass\ProviderCompilerPass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

use Mmoreram\BaseBundle\BaseBundle;
use Mmoreram\ControllerExtraBundle\CompilerPass\PaginatorCompilerPass;
use Mmoreram\ControllerExtraBundle\CompilerPass\ResolverCompilerPass;
use Mmoreram\ControllerExtraBundle\DependencyInjection\ControllerExtraExtension;

/**
 * ControllerExtraBundle, an extension of Bundle.
 */
class ControllerExtraBundle extends BaseBundle
{
    /**
     * Boots the Bundle.
     */
    public function boot()
    {
        $this->registerAnnotations([
            '@ControllerExtraBundle/Annotation/LoadEntity.php',
            '@ControllerExtraBundle/Annotation/Flush.php',
            '@ControllerExtraBundle/Annotation/CreateForm.php',
            '@ControllerExtraBundle/Annotation/Get.php',
            '@ControllerExtraBundle/Annotation/ToJsonResponse.php',
            '@ControllerExtraBundle/Annotation/Log.php',
            '@ControllerExtraBundle/Annotation/CreatePaginator.php',
            '@ControllerExtraBundle/Annotation/Post.php',
        ]);
    }

    /**
     * Returns the bundle's container extension.
     *
     * @return ExtensionInterface|null The container extension
     *
     * @throws \LogicException
     */
    public function getContainerExtension()
    {
        return new ControllerExtraExtension();
    }

    /**
     * Return a CompilerPass instance array.
     *
     * @return CompilerPassInterface[]
     */
    public function getCompilerPasses() : array
    {
        return [
            new ResolverCompilerPass(),
            new PaginatorCompilerPass(),
            new ProviderCompilerPass(),
        ];
    }

    /**
     * Register annotations.
     *
     * @param string[] Annotations
     */
    private function registerAnnotations(array $annotations)
    {
        $kernel = $this
            ->container
            ->get('kernel');

        foreach ($annotations as $annotation) {
            AnnotationRegistry::registerFile($kernel
                ->locateResource($annotation)
            );
        }
    }
}
