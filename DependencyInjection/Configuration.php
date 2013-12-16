<?php

/**
 * Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 */

namespace Mmoreram\ControllerExtraBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Dependency Injection configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('controller_extra');

        $rootNode
            ->children()
                ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('active')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('flush')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('active')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('paginator')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('active')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('number_default')
                            ->defaultValue(10)
                        ->end()
                        ->scalarNode('page_default')
                            ->defaultValue(1)
                        ->end()
                        ->scalarNode('orderby_field_default')
                            ->defaultValue('id')
                        ->end()
                        ->scalarNode('orderby_mode_default')
                            ->defaultValue('desc')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}