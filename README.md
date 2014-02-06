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
    * [Configuration](#configuration)
    * [Tests](#tests)
2. [Annotations](#annotations)
    * [@Form](#form)
    * [@Flush](#flush)
    * [@Entity](#entity)
    * [@Log](#log)
3. [Contributing](#contribute)

# Installing/Configuring

## Tags

* Use version `1.0-dev` for last updated. Alias of `dev-master`.
* Use last stable version tag to stay in a stable release.

## Installing [ControllerExtraBundle](https://github.com/mmoreram/controller-extra-bundle)

You have to add require line into you composer.json file

``` yml
"require": {
    "php": ">=5.3.3",
    "symfony/symfony": "2.3.*",

    "mmoreram/controller-extra-bundle": "1.0.*@dev",
}
```

Then you have to use composer to update your project dependencies

``` bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar update
```

And register the bundle in your appkernel.php file

``` php
return array(
    // ...
    new Mmoreram\ControllerExtraBundle\ControllerExtraBundle(),
    // ...
);
```

## Tests
You can test this bundle with this command

``` bash
$ php vendor/phpunit/phpunit/phpunit.php
```

## Configuration

``` yml
controller_extra:
    form:
        active: true
    flush:
        active: true
        default_manager: default
    entity:
        active: true
    log:
        active: true
        default_level: info
        default_execute: pre
```

# Annotations

This bundle provide a reduced but useful set of annotations for your controller

## @Form

Provides form injection in your controller actions. This annotation only needs a name to be defined in, where you must define namespace where your form is placed.

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\Form;
use Symfony\Component\Form\AbstractType;

/**
 * Simple controller method
 *
 * @Form(
 *      class = "\Mmoreram\CustomBundle\Form\Type\UserType",
 *      name  = "userType"
 * )
 */
public function indexAction(AbstractType $userType)
{
}
```

> By default, if `name` option is not set, the generated object will be placed in a parameter named `$form`.

You can not just define your Type location using the namespace, in which case a new AbstractType element will be created. but you can also define it using service alias, in which case this bundle will return an instance using Symfony DI.

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\Form;
use Symfony\Component\Form\AbstractType;

/**
 * Simple controller method
 *
 * @Form(
 *      class = "user_type",
 *      name  = "userType"
 * )
 */
public function indexAction(AbstractType $userType)
{
}
```

This annotation allows you to not only create an instance of FormType, but also allows you to inject a From object or a FormView object

To inject a Form object you only need to cast method value as such.

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\Form as AnnotationForm;
use Symfony\Component\Form\Form;

/**
 * Simple controller method
 *
 * @AnnotationForm(
 *      class = "user_type",
 *      name  = "userForm"
 * )
 */
public function indexAction(Form $userForm)
{
}
```

You can also, using [SensioFrameworkExtraBundle][1]'s [ParamConverter][2], create a Form object with an previously created entity. you can define this entity using `entity` parameter.

``` php
<?php

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Mmoreram\ControllerExtraBundle\Annotation\Form as AnnotationForm;
use Symfony\Component\Form\Form;

/**
 * Simple controller method
 *
 * @Route("/user/{id}")
 * @ParamConverter("user", class="MmoreramCustomBundle:User")
 * @AnnotationForm(
 *      class  = "user_type",
 *      entity = "user"
 *      name   = "userForm",
 * )
 */
public function indexAction(User $user, Form $userForm)
{
}
```

To handle current request, you can set `handleRequest` to true. By default this value is set to `false`


``` php
<?php

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Mmoreram\ControllerExtraBundle\Annotation\Form as AnnotationForm;
use Symfony\Component\Form\Form;

/**
 * Simple controller method
 *
 * @Route("/user/{id}")
 * @ParamConverter("user", class="MmoreramCustomBundle:User")
 * @AnnotationForm(
 *      class         = "user_type",
 *      entity        = "user"
 *      handleRequest = true,
 *      name          = "userForm",
 * )
 */
public function indexAction(User $user, Form $userForm)
{
}
```

To inject a FormView object you only need to cast method variable as such.

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\Form;
use Symfony\Component\Form\FormView;

/**
 * Simple controller method
 *
 * @Form(
 *      class = "user_type",
 *      name  = "userFormView"
 * )
 */
public function indexAction(FormView $userFormView)
{
}
```

## @Flush

Flush annotation allows you to flush entityManager at the end of request using kernel.response event

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\Flush;

/**
 * Simple controller method
 *
 * @Flush
 */
public function indexAction()
{
}
```

If not otherwise specified, default Doctrine Manager will be flushed with this annotation. You can overwrite default Mangager in your config.yml file

``` yml
controller_extra:
    flush:
        default_manager: my_custom_manager
```

You can also overwrite overwrite this value in every single Flush Annotation instance defining `manager` value

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\Flush;

/**
 * Simple controller method
 *
 * @Flush(
 *      manager = "my_customer_manager"
 * )
 */
public function indexAction()
{
}
```

> If multiple @Mmoreram\Flush are defined in same action, last instance will overwrite previous. Anyway just one instance should be defined.

## @Log

Log annotation allows you to log any plain message before or after controller action execution

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\Log;

/**
 * Simple controller method
 *
 * @Log("Executing index Action")
 */
public function indexAction()
{
}
```

You can define the level of the message. You can define default one if none is specified overwriting it in your `config.yml` file.

``` yml
controller_extra:
    log:
        default_level: warning
```

Every Annotation instance can overwrite this value using `level` field.

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\Flush;

/**
 * Simple controller method
 *
 * @Log(
 *      value   = "Executing index Action",
 *      level   = @Log::LVL_WARNING
 * )
 */
public function indexAction()
{
}
```

Several levels can be used, as defined in [Psr\Log\LoggerInterface][6] interface

* @Mmoreram\Log::LVL_EMERG
* @Mmoreram\Log::LVL_CRIT
* @Mmoreram\Log::LVL_ERR
* @Mmoreram\Log::LVL_WARN
* @Mmoreram\Log::LVL_NOTICE
* @Mmoreram\Log::LVL_INFO
* @Mmoreram\Log::LVL_DEBUG
* @Mmoreram\Log::LVL_LOG


You can also define the execution of the log. You can define default one if none is specified overwriting it in your `config.yml` file.

``` yml
controller_extra:
    log:
        default_execute: pre
```

Every Annotation instance can overwrite this value using `level` field.

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\Flush;

/**
 * Simple controller method
 *
 * @Log(
 *      value   = "Executing index Action",
 *      execute = @Log::EXEC_POST
 * )
 */
public function indexAction()
{
}
```

Several executions can be used,

* @Mmoreram\Log::EXEC_PRE - Logged before controller execution
* @Mmoreram\Log::EXEC_POST - Logged after controller execution
* @Mmoreram\Log::EXEC_BOTH - Logged both


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
[6]: https://github.com/php-fig/log/blob/master/Psr/Log/LoggerInterface.php
