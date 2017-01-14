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

use Symfony\Component\Config\Definition\ConfigurationInterface;

use Mmoreram\BaseBundle\DependencyInjection\BaseExtension;

/**
 * This is the class that loads and manages your bundle configuration.
 */
final class ControllerExtraExtension extends BaseExtension
{
    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * @return string The alias
     *
     * @api
     */
    public function getAlias()
    {
        return 'controller_extra';
    }

    /**
     * Get the Config file location.
     *
     * @return string
     */
    protected function getConfigFilesLocation() : string
    {
        return __DIR__ . '/../Resources/config';
    }

    /**
     * Config files to load.
     *
     * Each array position can be a simple file name if must be loaded always,
     * or an array, with the filename in the first position, and a boolean in
     * the second one.
     *
     * As a parameter, this method receives all loaded configuration, to allow
     * setting this boolean value from a configuration value.
     *
     * return array(
     *      'file1.yml',
     *      'file2.yml',
     *      ['file3.yml', $config['my_boolean'],
     *      ...
     * );
     *
     * @param array $config Config definitions
     *
     * @return array Config files
     */
    protected function getConfigFiles(array $config) : array
    {
        return [
            'providers',
            'annotations_resolver',
            ['resolver_form', $config['form']['active']],
            ['resolver_flush', $config['flush']['active']],
            ['resolver_entity', $config['entity']['active']],
            ['resolver_log', $config['log']['active']],
            ['resolver_json_response', $config['json_response']['active']],
            ['resolver_paginator', $config['paginator']['active']],
            ['resolver_get', $config['get']['active']],
            ['resolver_post', $config['post']['active']],
        ];
    }

    /**
     * Return a new Configuration instance.
     *
     * If object returned by this method is an instance of
     * ConfigurationInterface, extension will use the Configuration to read all
     * bundle config definitions.
     *
     * Also will call getParametrizationValues method to load some config values
     * to internal parameters.
     *
     * @return ConfigurationInterface|null
     */
    protected function getConfigurationInstance() : ? ConfigurationInterface
    {
        return new ControllerExtraConfiguration($this->getAlias());
    }

    /**
     * Load Parametrization definition.
     *
     * return array(
     *      'parameter1' => $config['parameter1'],
     *      'parameter2' => $config['parameter2'],
     *      ...
     * );
     *
     * @param array $config Bundles config values
     *
     * @return array
     */
    protected function getParametrizationValues(array $config) : array
    {
        return [
            /**
             * Common parameters.
             */
            'controller_extra.resolver_priority' => $config['resolver_priority'],
            'controller_extra.request' => $config['request'],

            /**
             * Form parameters.
             */
            'controller_extra.form_active' => $config['form']['active'],
            'controller_extra.form_default_name' => $config['form']['default_name'],

            /**
             * Flush parameters.
             */
            'controller_extra.flush_active' => $config['flush']['active'],
            'controller_extra.flush_default_manager' => $config['flush']['default_manager'],

            /**
             * Entity parameters.
             */
            'controller_extra.entity_active' => $config['entity']['active'],
            'controller_extra.entity_default_name' => $config['entity']['default_name'],
            'controller_extra.entity_default_persist' => $config['entity']['default_persist'],
            'controller_extra.entity_fallback_mapping' => $config['entity']['fallback_mapping'],
            'controller_extra.entity_default_factory_method' => $config['entity']['default_factory_method'],
            'controller_extra.entity_default_factory_static' => $config['entity']['default_factory_static'],

            /**
             * JsonResponse parameters.
             */
            'controller_extra.json_response_active' => $config['json_response']['active'],
            'controller_extra.json_response_default_status' => $config['json_response']['default_status'],
            'controller_extra.json_response_default_error_status' => $config['json_response']['default_error_status'],
            'controller_extra.json_response_default_headers' => $config['json_response']['default_headers'],

            /**
             * Log parameters.
             */
            'controller_extra.log_active' => $config['log']['active'],
            'controller_extra.log_default_level' => $config['log']['default_level'],
            'controller_extra.log_default_execute' => $config['log']['default_execute'],

            /**
             * Paginator parameters.
             */
            'controller_extra.paginator_active' => $config['paginator']['active'],
            'controller_extra.paginator_default_name' => $config['paginator']['default_name'],
            'controller_extra.paginator_default_page' => $config['paginator']['default_page'],
            'controller_extra.paginator_default_limit_per_page' => $config['paginator']['default_limit_per_page'],

            /**
             * Get parameters.
             */
            'controller_extra.get_active' => $config['get']['active'],

            /**
             * Post parameters.
             */
            'controller_extra.post_active' => $config['post']['active'],
        ];
    }
}
