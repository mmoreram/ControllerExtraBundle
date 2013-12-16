ControllerExtra for Symfony2
=====

This bundle provides a collection of annotations for Symfony2 Controllers, designed to streamline the creation of certain objects and enable smaller and concise actions.

Table of contents
-----
1. [Installing/Configuring](#installingconfiguring)
    * [Tags](#tags)
    * [Installing ControllerExtra](#installing-controllerextra)
2. [Annotations](#annotations)
    * [Mmoreram\Form](#mmoreram-form)
    * [Mmoreram\Flush](#mmoreram-flush)
    * [Mmoreram\Paginator](#mmoreram-paginator)
    * [Mmoreram\Log](#mmoreram-log)
3. [Contributing](#contribute)

Installing/Configuring
-----

## Tags

* Use version `1.0-dev` for last updated. Alias of `dev-master`.
* Use last stable version tag to stay in a stable release.

## Installing [ControllerExtra](https://github.com/mmoreram/-bundle)

You have to add require line into you composer.json file

    "require": {
        "php": ">=5.3.3",
        "symfony/symfony": "2.3.*",
        ...
        "mmoreram/controller-annotations-bundle": "dev-master"
    },

Then you have to use composer to update your project dependencies

    php composer.phar update

And register the bundle in your appkernel.php file

    return array(
        // ...
        new Mmoreram\ControllerExtraBundle\ControllerExtraBundle(),
        // ...
    );

## Configuration

Annotations
-----

## Mmoreram\Form

Provides form injection in your controllers.

## Mmoreram\Flush

Allow you to flush entityManager at the end of the request, using kernel.terminate event

## Mmoreram\Paginator

Creates a KnpPaginator Object given a matched route

## Mmoreram\Log

Automatize controller and method access log