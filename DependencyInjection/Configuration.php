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

namespace Mmoreram\ControllerExtraBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Mmoreram\ControllerExtraBundle\Annotation\Log as AnnotationLog;

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

                /**
                 * Bundle config definition
                 */
                ->integerNode('resolver_priority')
                    ->defaultValue(-8)
                ->end()

                /**
                 * Default factory definition
                 */
                ->arrayNode('factory')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('default_method')
                            ->defaultValue('create')
                        ->end()
                        ->booleanNode('default_static')
                            ->defaultValue(true)
                        ->end()
                    ->end()
                ->end()

                /**
                 * Form config definition
                 */
                ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('active')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('default_name')
                            ->defaultValue('form')
                        ->end()
                    ->end()
                ->end()

                /**
                 * Flush config definition
                 */
                ->arrayNode('flush')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('active')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('default_manager')
                            ->defaultValue('default')
                        ->end()
                    ->end()
                ->end()

                /**
                 * Entity config definition
                 */
                ->arrayNode('entity')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('active')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('default_name')
                            ->defaultValue('entity')
                        ->end()
                        ->scalarNode('default_persist')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()

                /**
                 * Bundle config definition
                 */
                ->arrayNode('json_response')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('active')
                            ->defaultTrue()
                        ->end()
                        ->integerNode('default_status')
                            ->defaultValue(200)
                        ->end()
                        ->arrayNode('default_headers')
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')
                            ->end()
                            ->defaultValue(array())
                        ->end()
                    ->end()
                ->end()

                /**
                 * Log config definition
                 */
                ->arrayNode('log')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('active')
                            ->defaultTrue()
                        ->end()
                        ->enumNode('default_level')
                            ->values(array(
                                AnnotationLog::LVL_EMERG,
                                AnnotationLog::LVL_ALERT,
                                AnnotationLog::LVL_CRIT,
                                AnnotationLog::LVL_ERR,
                                AnnotationLog::LVL_WARNING,
                                AnnotationLog::LVL_INFO,
                                AnnotationLog::LVL_DEBUG,
                                AnnotationLog::LVL_LOG,
                            ))
                            ->defaultValue(AnnotationLog::LVL_INFO)
                        ->end()
                        ->enumNode('default_execute')
                            ->values(array(
                                AnnotationLog::EXEC_PRE,
                                AnnotationLog::EXEC_POST,
                                AnnotationLog::EXEC_BOTH,
                            ))
                            ->defaultValue(AnnotationLog::EXEC_PRE)
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('paginator')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('active')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('default_name')
                            ->defaultValue('paginator')
                        ->end()
                        ->integerNode('default_page')
                            ->defaultValue(1)
                        ->end()
                        ->integerNode('default_limit_per_page')
                            ->defaultValue(10)
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
