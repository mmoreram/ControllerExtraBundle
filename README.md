ControllerExtra for Symfony2
=====

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/66e58cb8-cc5c-4899-8082-80cf23ef15af/mini.png)](https://insight.sensiolabs.com/projects/66e58cb8-cc5c-4899-8082-80cf23ef15af)
[![Build Status](https://travis-ci.org/mmoreram/ControllerExtraBundle.png?branch=master)](https://travis-ci.org/mmoreram/ControllerExtraBundle)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/mmoreram/ControllerExtraBundle/badges/quality-score.png?s=e960930a8cd10d62ec092248d14af620aa96ea9a)](https://scrutinizer-ci.com/g/mmoreram/ControllerExtraBundle/)
[![Latest Stable Version](https://poser.pugx.org/mmoreram/controller-extra-bundle/v/stable.png)](https://packagist.org/packages/mmoreram/controller-extra-bundle)
[![Latest Unstable Version](https://poser.pugx.org/mmoreram/controller-extra-bundle/v/unstable.png)](https://packagist.org/packages/mmoreram/controller-extra-bundle)
[![Dependency Status](https://www.versioneye.com/user/projects/52b09eefec137588e200007e/badge.png)](https://www.versioneye.com/user/projects/52b09eefec137588e200007e)
[![Total Downloads](https://poser.pugx.org/mmoreram/controller-extra-bundle/downloads.png)](https://packagist.org/packages/mmoreram/controller-extra-bundle)

This bundle provides a collection of annotations for Symfony2 Controllers, designed to streamline the creation of certain objects and enable smaller and more concise actions.

Table of contents
-----
1. [Installing/Configuring](#installingconfiguring)
    * [Tags](#tags)
    * [Installing ControllerExtraBundle](#installing-controllerextrabundle)
2. [Annotations](#annotations)
    * [Mmoreram\Form](#mmoreram-form)
    * [Mmoreram\Flush](#mmoreram-flush)
3. [Contributing](#contribute)

# Installing/Configuring

## Tags

* Use version `1.0-dev` for last updated. Alias of `dev-master`.
* Use last stable version tag to stay in a stable release.

## Installing [ControllerExtraBundle](https://github.com/mmoreram/controller-extra-bundle)

You have to add require line into you composer.json file

    "require": {
        "php": ">=5.3.3",
        "symfony/symfony": "2.3.*",
        ...
        "mmoreram/controller-annotations-bundle": "1.0-dev"
    },

Then you have to use composer to update your project dependencies

    php composer.phar update

And register the bundle in your appkernel.php file

    return array(
        // ...
        new Mmoreram\ControllerExtraBundle\ControllerExtraBundle(),
        // ...
    );

# Configuration

    controller_extra:
        form:
            active: true
        flush:
            active: true
            default_manager: default

# Annotations

This bundle provide a reduced but useful set of annotations for your controller

## Mmoreram\Form

Provides form injection in your controller actions. This annotation only needs a name to be defined in, where you must define namespace where your form is placed.

    use Mmoreram\ControllerExtraBundle\Annotation as Mmoreram;
    use Symfony\Component\Form\AbstractType;

    /**
     * Simple controller method
     *
     * @Mmoreram\Form(
            name     = "\Mmoreram\CustomBundle\Form\Type\UserType",
     *      variable = "userType"
     * )
     */
    public function indexAction(AbstractType $userType)
    {
    }

> By default, if `variable` option is not set, generated object will be placed in a parameter named `$form`.

You can not just define your Type location using the namespace, in which case a new AbstractType element will be created. but you can also define it using service alias, in which case this bundle will return instance, using dependency injection.

    use Mmoreram\ControllerExtraBundle\Annotation as Mmoreram;
    use Symfony\Component\Form\AbstractType;

    /**
     * Simple controller method
     *
     * @Mmoreram\Form(
            name     = "user_type",
     *      variable = "userType"
     * )
     */
    public function indexAction(AbstractType $userType)
    {
    }

This annotation allows you to not only create an instance of FormType, but also allows you to inject Form object or FormView object rather simple FormType.

To inject a Form object you only need to cast method variable as such.

    use Mmoreram\ControllerExtraBundle\Annotation as Mmoreram;
    use Symfony\Component\Form\Form;

    /**
     * Simple controller method
     *
     * @Mmoreram\Form(
            name     = "user_type",
     *      variable = "userForm"
     * )
     */
    public function indexAction(Form $userForm)
    {
    }

You can also, using [SensioFrameworkExtraBundle][1]'s [ParamConverter][2], create a Form object with an previously created entity. you can define this entity by using `entity` parameter.

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
    use Mmoreram\ControllerExtraBundle\Annotation as Mmoreram;
    use Symfony\Component\Form\Form;

    /**
     * Simple controller method
     *
     * @Route("/user/{id}")
     * @ParamConverter("user", class="MmoreramCustomBundle:User")
     * @Mmoreram\Form(
            name        = "user_type",
     *      variable    = "userForm",
     *      entity      = "user"
     * )
     */
    public function indexAction(User $user, Form $userForm)
    {
    }

To inject a FormView object you only need to cast method variable as such.

    use Mmoreram\ControllerExtraBundle\Annotation as Mmoreram;
    use Symfony\Component\Form\FormView;

    /**
     * Simple controller method
     *
     * @Mmoreram\Form(
            name     = "user_type",
     *      variable = "userFormView"
     * )
     */
    public function indexAction(FormView $userFormView)
    {
    }

## Mmoreram\Flush

Allow you to flush entityManager at the end of the request, using kernel.response event

    use Mmoreram\ControllerExtraBundle\Annotation as Mmoreram;
    use Symfony\Component\Form\FormView;

    /**
     * Simple controller method
     *
     * @Mmoreram\Flush
     */
    public function indexAction()
    {
    }

If not otherwise specified, default Doctrine Manager will be flushed with this annotation.
You can overwrite default Mangager in your config.yml file

    controller_extra:
        flush:
            default_manager: my_custom_manager

You can also overwrite overwrite this value in every single Flush Annotation instance by defining `manager` value

    use Mmoreram\ControllerExtraBundle\Annotation as Mmoreram;
    use Symfony\Component\Form\FormView;

    /**
     * Simple controller method
     *
     * @Mmoreram\Flush(
     *      manager = "my_customer_manager"
     * )
     */
    public function indexAction()
    {
    }

> If multiple @Mmoreram\Flush are defined in same action, last instance will overwrite previous. Anyway just one instance should be defined.

# Contributing

All code is Symfony2 Code formatted, so every pull request must validate phpcs standards.
You should read [Symfony2 coding standards](http://symfony.com/doc/current/contributing/code/standards.html) and install [this](https://github.com/opensky/Symfony2-coding-standard) CodeSniffer to check all code is validated.

There is also a policy for contributing to this project. All pull request must be all explained step by step, to make us more understandable and easier to merge pull request. All new features must be tested with PHPUnit.

If you'd like to contribute, please read the [Contributing Code][3] part of the documentation. If you're submitting a pull request, please follow the guidelines in the [Submitting a Patch][4] section and use the [Pull Request Template][5].

[1]: https://github.com/sensiolabs/SensioFrameworkExtraBundle
[2]: http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
[3]: http://symfony.com/doc/current/contributing/code/index.html
[4]: http://symfony.com/doc/current/contributing/code/patches.html#check-list
[5]: http://symfony.com/doc/current/contributing/code/patches.html#make-a-pull-request