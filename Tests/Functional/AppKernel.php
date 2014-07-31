<?php

/**
 * Gearman Bundle for Symfony2
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @since 2013
 */

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

use Mmoreram\ControllerExtraBundle\ControllerExtraBundle;
use Mmoreram\ControllerExtraBundle\Tests\FakeBundle\FakeBundle;

/**
 * AppKernel for testing
 */
class AppKernel extends Kernel
{
    /**
     * Registers all needed bundles
     */
    public function registerBundles()
    {
        return array(
            new FrameworkBundle(),
            new DoctrineBundle(),
            new MonologBundle(),
            new ControllerExtraBundle(),
            new FakeBundle(),
        );
    }

    /**
     * Setup configuration file.
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(dirname(__FILE__) . '/config.yml');
    }

    /**
     * Return Cache dir
     *
     * @return string
     */
    public function getCacheDir()
    {
        return  sys_get_temp_dir() .
        DIRECTORY_SEPARATOR .
        'ControllerExtraBundle' .
        DIRECTORY_SEPARATOR .
        '/Cache/';

    }

    /**
     * Return log dir
     *
     * @return string
     */
    public function getLogDir()
    {
        return  sys_get_temp_dir() .
        DIRECTORY_SEPARATOR .
        'ControllerExtraBundle' .
        DIRECTORY_SEPARATOR .
        '/Log/';
    }
}
