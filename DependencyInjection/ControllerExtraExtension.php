<?php

/**
 * Controller Extra Bundle
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
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

        $container->setParameter('mmoreram.controllerextra.form_active', $config['form']['active']);

        $container->setParameter('mmoreram.controllerextra.flush_active', $config['flush']['active']);
        $container->setParameter('mmoreram.controllerextra.flush_default_manager', $config['flush']['default_manager']);

        $container->setParameter('mmoreram.controllerextra.log_active', $config['log']['active']);
        $container->setParameter('mmoreram.controllerextra.log_default_handler', $config['log']['default_handler']);

        $container->setParameter('mmoreram.controllerextra.paginator_active', $config['paginator']['active']);
        $container->setParameter('mmoreram.controllerextra.paginator_number_default', $config['paginator']['number_default']);
        $container->setParameter('mmoreram.controllerextra.paginator_page_default', $config['paginator']['page_default']);
        $container->setParameter('mmoreram.controllerextra.paginator_orderby_field_default', $config['paginator']['orderby_field_default']);
        $container->setParameter('mmoreram.controllerextra.paginator_orderby_mode_default', $config['paginator']['orderby_mode_default']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('event_listeners.yml');

        if ($config['form']['active']) {

            $loader->load('resolver_form.yml');
        }

        if ($config['flush']['active']) {

            $loader->load('resolver_flush.yml');
        }

        if ($config['log']['active']){

            $loader->load('resolver_log.yml');
        }

        if ($config['paginator']['active']) {

            $loader->load('resolver_paginator.yml');
        }
    }
}
