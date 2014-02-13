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
                ->scalarNode('resolver_priority')
                    ->defaultValue(-8)
                ->end()

                /**
                 * Form config definition
                 */
                ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('active')
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
                        ->scalarNode('active')
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
                        ->scalarNode('active')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('default_name')
                            ->defaultValue('entity')
                        ->end()
                    ->end()
                ->end()

                /**
                 * Log config definition
                 */
                ->arrayNode('log')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('active')
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
            ->end();

        return $treeBuilder;
    }
}
