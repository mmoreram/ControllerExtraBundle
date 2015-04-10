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

namespace Mmoreram\ControllerExtraBundle;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use Mmoreram\ControllerExtraBundle\CompilerPass\PaginatorCompilerPass;
use Mmoreram\ControllerExtraBundle\CompilerPass\ResolverCompilerPass;

/**
 * ControllerExtraBundle, an extension of Bundle
 */
class ControllerExtraBundle extends Bundle
{
    /**
     * Builds bundle
     *
     * @param ContainerBuilder $container Container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        /**
         * Adds compiler passes
         */
        $container->addCompilerPass(new ResolverCompilerPass());
        $container->addCompilerPass(new PaginatorCompilerPass());
    }
}
