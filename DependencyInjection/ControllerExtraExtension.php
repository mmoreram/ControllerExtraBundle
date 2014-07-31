<?php

/**
 * This file is part of the ControllerExtraBundle for Symfony2.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace Mmoreram\ControllerExtraBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 */
class ControllerExtraExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $config    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $config);

        /**
         * Common parameters
         */
        $container->setParameter(
            'mmoreram.controllerextra.resolver_priority',
            $config['resolver_priority']
        );

        /**
         * Factory parameters
         */
        $container->setParameter(
            'mmoreram.controllerextra.factory_default_method',
            $config['factory']['default_method']
        );

        $container->setParameter(
            'mmoreram.controllerextra.factory_default_static',
            $config['factory']['default_static']
        );

        /**
         * Form parameters
         */
        $container->setParameter(
            'mmoreram.controllerextra.form_active',
            $config['form']['active']
        );

        $container->setParameter(
            'mmoreram.controllerextra.form_default_name',
            $config['form']['default_name']
        );

        /**
         * Flush parameters
         */
        $container->setParameter(
            'mmoreram.controllerextra.flush_active',
            $config['flush']['active']
        );

        $container->setParameter(
            'mmoreram.controllerextra.flush_default_manager',
            $config['flush']['default_manager']
        );

        /**
         * Entity parameters
         */
        $container->setParameter(
            'mmoreram.controllerextra.entity_active',
            $config['entity']['active']
        );

        $container->setParameter(
            'mmoreram.controllerextra.entity_default_name',
            $config['entity']['default_name']
        );

        $container->setParameter(
            'mmoreram.controllerextra.entity_default_persist',
            $config['entity']['default_persist']
        );

        /**
         * JsonResponse parameters
         */
        $container->setParameter(
            'mmoreram.controllerextra.json_response_active',
            $config['json_response']['active']
        );

        $container->setParameter(
            'mmoreram.controllerextra.json_response_default_status',
            $config['json_response']['default_status']
        );

        $container->setParameter(
            'mmoreram.controllerextra.json_response_default_headers',
            $config['json_response']['default_headers']
        );

        /**
         * Log parameters
         */
        $container->setParameter(
            'mmoreram.controllerextra.log_active',
            $config['log']['active']
        );

        $container->setParameter(
            'mmoreram.controllerextra.log_default_level',
            $config['log']['default_level']
        );

        $container->setParameter(
            'mmoreram.controllerextra.log_default_execute',
            $config['log']['default_execute']
        );

        /**
         * Paginator parameters
         */
        $container->setParameter(
            'mmoreram.controllerextra.paginator_active',
            $config['paginator']['active']
        );

        $container->setParameter(
            'mmoreram.controllerextra.paginator_default_name',
            $config['paginator']['default_name']
        );

        $container->setParameter(
            'mmoreram.controllerextra.paginator_default_page',
            $config['paginator']['default_page']
        );

        $container->setParameter(
            'mmoreram.controllerextra.paginator_default_limit_per_page',
            $config['paginator']['default_limit_per_page']
        );

        /**
         * Object manager parameters
         */
        $container->setParameter(
            'mmoreram.controllerextra.object_manager_active',
            $config['object_manager']['active']
        );

        $container->setParameter(
            'mmoreram.controllerextra.object_manager_default_name',
            $config['object_manager']['default_name']
        );

        /**
         * Load config files
         */
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('classes.yml');
        $loader->load('externals.yml');
        $loader->load('providers.yml');
        $loader->load('event_listeners.yml');

        $this->loadResolverConfiguration($loader, $config);
    }

    /**
     * Load resolver config files
     *
     * @param YamlFileLoader $loader Loader
     * @param array          $config Config
     *
     * @return ControllerExtraExtension self Object
     */
    public function loadResolverConfiguration(
        YamlFileLoader $loader,
        array $config
    )
    {
        /**
         * Only load form resolver config definition if is active
         */
        if ($config['form']['active']) {

            $loader->load('resolver_form.yml');
        }

        /**
         * Only load flush resolver config definition if is active
         */
        if ($config['flush']['active']) {

            $loader->load('resolver_flush.yml');
        }

        /**
         * Only load entity resolver config definition if is active
         */
        if ($config['entity']['active']) {

            $loader->load('resolver_entity.yml');
        }

        /**
         * Only load log resolver config definition if is active
         */
        if ($config['log']['active']) {

            $loader->load('resolver_log.yml');
        }

        /**
         * Only load json resolver config definition if is active
         */
        if ($config['json_response']['active']) {

            $loader->load('resolver_json_response.yml');
        }

        /**
         * Only load paginator resolver config definition if is active
         */
        if ($config['paginator']['active']) {

            $loader->load('resolver_paginator.yml');
        }

        /**
         * Only load object manager resolver config definition if is active
         */
        if ($config['object_manager']['active']) {

            $loader->load('resolver_object_manager.yml');
        }

        return $this;
    }
}
