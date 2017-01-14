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

namespace Mmoreram\ControllerExtraBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

use Mmoreram\BaseBundle\DependencyInjection\BaseConfiguration;
use Mmoreram\ControllerExtraBundle\Annotation\Log as AnnotationLog;
use Mmoreram\ControllerExtraBundle\Provider\RequestParameterProvider;

/**
 * Class ControllerExtraConfiguration.
 */
final class ControllerExtraConfiguration extends BaseConfiguration
{
    /**
     * Configure the root node.
     *
     * @param ArrayNodeDefinition $rootNode Root node
     */
    protected function setupTree(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()

                /**
                 * Bundle config definition.
                 */
                ->integerNode('resolver_priority')
                    ->defaultValue(-8)
                ->end()

                /**
                 * Provider request.
                 */
                ->enumNode('request')
                    ->values([
                        RequestParameterProvider::CURRENT_REQUEST,
                        RequestParameterProvider::MASTER_REQUEST,
                    ])
                    ->defaultValue(RequestParameterProvider::CURRENT_REQUEST)
                ->end()

                /**
                 * Form config definition.
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
                 * Flush config definition.
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
                 * Entity config definition.
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
                        ->booleanNode('fallback_mapping')
                            ->defaultFalse()
                        ->end()
                         ->scalarNode('default_factory_method')
                            ->defaultValue('create')
                        ->end()
                        ->booleanNode('default_factory_static')
                            ->defaultValue(true)
                        ->end()
                    ->end()
                ->end()

                /**
                 * Bundle config definition.
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
                        ->integerNode('default_error_status')
                            ->defaultValue(500)
                        ->end()
                        ->arrayNode('default_headers')
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')
                            ->end()
                            ->defaultValue([])
                        ->end()
                    ->end()
                ->end()

                /**
                 * Log config definition.
                 */
                ->arrayNode('log')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('active')
                            ->defaultTrue()
                        ->end()
                        ->enumNode('default_level')
                            ->values([
                                AnnotationLog::LVL_EMERG,
                                AnnotationLog::LVL_ALERT,
                                AnnotationLog::LVL_CRIT,
                                AnnotationLog::LVL_ERR,
                                AnnotationLog::LVL_WARNING,
                                AnnotationLog::LVL_INFO,
                                AnnotationLog::LVL_DEBUG,
                                AnnotationLog::LVL_LOG,
                            ])
                            ->defaultValue(AnnotationLog::LVL_INFO)
                        ->end()
                        ->enumNode('default_execute')
                            ->values([
                                AnnotationLog::EXEC_PRE,
                                AnnotationLog::EXEC_POST,
                                AnnotationLog::EXEC_BOTH,
                            ])
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

                /**
                 * Get config definition.
                 */
                ->arrayNode('get')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('active')
                                ->defaultTrue()
                        ->end()
                    ->end()
                ->end()

                /**
                 * Post config definition.
                 */
                ->arrayNode('post')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('active')
                                ->defaultTrue()
                            ->end()
                        ->end()
                    ->end()
                ->end()

            ->end();
    }
}
