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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 */
class ControllerExtraExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        /**
         * Form parameters
         */
        $container->setParameter('mmoreram.controllerextra.form_active', $config['form']['active']);

        /**
         * Flush parameters
         */
        $container->setParameter('mmoreram.controllerextra.flush_active', $config['flush']['active']);
        $container->setParameter('mmoreram.controllerextra.flush_default_manager', $config['flush']['default_manager']);

        /**
         * Log parameters
         */
        $container->setParameter('mmoreram.controllerextra.log_active', $config['log']['active']);
        $container->setParameter('mmoreram.controllerextra.log_default_handler', $config['log']['default_handler']);
        $container->setParameter('mmoreram.controllerextra.log_default_execute', $config['log']['default_execute']);

        /**
         * Load config files
         */
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
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
    public function loadResolverConfiguration(YamlFileLoader $loader, array $config)
    {
        /**
         * Only load resolver form config definition if is active
         */
        if ($config['form']['active']) {

            $loader->load('resolver_form.yml');
        }

        /**
         * Only load resolver flush config definition if is active
         */
        if ($config['flush']['active']) {

            $loader->load('resolver_flush.yml');
        }

        /**
         * Only load resolver log config definition if is active
         */
        if ($config['log']['active']){

            $loader->load('resolver_log.yml');
        }

        return $this;
    }
}
