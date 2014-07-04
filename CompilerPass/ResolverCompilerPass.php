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

namespace Mmoreram\ControllerExtraBundle\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Resolver compiler pass
 */
class ResolverCompilerPass implements CompilerPassInterface
{
    /**
     * Every service tagged as controllerextra.annotation will be processed
     *
     * @param ContainerBuilder $container Container
     */
    public function process(ContainerBuilder $container)
    {
        /**
         * We get our eventlistener
         */
        $definition = $container->getDefinition(
            'mmoreram.controllerextra.event_listeners.resolver_event_listener'
        );

        /**
         * We get all tagged services
         */
        $taggedServices = $container->findTaggedServiceIds(
            'controller_extra.annotation'
        );

        /**
         * We add every tagged Resolver into EventListener
         */
        foreach ($taggedServices as $id => $attributes) {

            $definition->addMethodCall(
                'addResolver',
                array(new Reference($id))
            );
        }
    }
}
