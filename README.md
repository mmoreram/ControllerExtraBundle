ControllerExtra for Symfony2
=====

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/66e58cb8-cc5c-4899-8082-80cf23ef15af/mini.png)](https://insight.sensiolabs.com/projects/66e58cb8-cc5c-4899-8082-80cf23ef15af)
[![Build Status](https://travis-ci.org/mmoreram/ControllerExtraBundle.png?branch=master)](https://travis-ci.org/mmoreram/ControllerExtraBundle)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/mmoreram/ControllerExtraBundle/badges/quality-score.png?s=e960930a8cd10d62ec092248d14af620aa96ea9a)](https://scrutinizer-ci.com/g/mmoreram/ControllerExtraBundle/)
[![Latest Stable Version](https://poser.pugx.org/mmoreram/controller-extra-bundle/v/stable.png)](https://packagist.org/packages/mmoreram/controller-extra-bundle)
[![Latest Unstable Version](https://poser.pugx.org/mmoreram/controller-extra-bundle/v/unstable.png)](https://packagist.org/packages/mmoreram/controller-extra-bundle)
[![Dependency Status](https://www.versioneye.com/user/projects/52b09eefec137588e200007e/badge.png)](https://www.versioneye.com/user/projects/52b09eefec137588e200007e)
[![Total Downloads](https://poser.pugx.org/mmoreram/controller-extra-bundle/downloads.png)](https://packagist.org/packages/mmoreram/controller-extra-bundle)

This bundle provides a collection of annotations for Symfony2 Controllers,
designed to streamline the creation of certain objects and enable smaller and
more concise actions.

Table of contents
-----
1. [Installing/Configuring](#installingconfiguring)
    * [Tags](#tags)
    * [Installing ControllerExtraBundle](#installing-controllerextrabundle)
    * [Configuration](#configuration)
    * [Tests](#tests)
1. [Bundle Annotations](#bundle-annotations)
    * [@Entity](#entity)
    * [@Form](#form)
    * [@Flush](#flush)
    * [@Log](#log)
1. [Custom annotations](#custom-annotations)
    * [Annotation](#annotation)
    * [Resolver](#resolver)
    * [Definition](#definition)
    * [Registration](#registration)
1. [Contributing](#contributing)

# Installing/Configuring

## Tags

* Use last unstable version ( alias of `dev-master` ) to stay in last commit
* Use last stable version tag to stay in a stable release.
* [![Latest Unstable Version](https://poser.pugx.org/mmoreram/controller-extra-bundle/v/unstable.png)](https://packagist.org/packages/mmoreram/controller-extra-bundle)
[![Latest Stable Version](https://poser.pugx.org/mmoreram/controller-extra-bundle/v/stable.png)](https://packagist.org/packages/mmoreram/controller-extra-bundle)

## Installing  [ControllerExtraBundle](https://github.com/mmoreram/controller-extra-bundle)

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

By default, all annotations are loaded, but any individual annotation can be
completely disabled by setting to false `active` parameter.

``` yml
controller_extra:
    resolver_priority: -8
    form:
        active: true
        default_name: form
    flush:
        active: true
        default_manager: default
    entity:
        active: true
        default_name: entity
    log:
        active: true
        default_level: info
        default_execute: pre
```

> ResolverEventListener is subscribed to `kernel.controller` event with 
> priority -8. This element can be configured and customized with 
> `resolver_priority` config value. If you need to get ParamConverter entities,
> make sure that this value is lower than 0. The reason is that this listener
> must be executed always after ParamConverter one.

# Bundle annotations

This bundle provide a reduced but useful set of annotations for your controller

## @Entity

Creates a simple empty entity, given a namespace, and places it as a method
parameter.

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\Entity;
use Mmoreram\ControllerExtraBundle\Entity\User;

/**
 * Simple controller method
 *
 * @Entity(
 *      class = "MmoreramCustomBundle:User",
 *      name  = "user"
 * )
 */
public function indexAction(User $user)
{
}
```

> By default, if `name` option is not set, the generated object will be placed
> in a parameter named `$entity`. This behaviour can be configured using
> `default_name` in configuration.

You can also use setters in Entity annotation. It means that you can simply call
entity setters using Request attributes.

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\Entity;
use Mmoreram\ControllerExtraBundle\Entity\Address;
use Mmoreram\ControllerExtraBundle\Entity\User;

/**
 * Simple controller method
 *
 * @Entiy(
 *      class = "MmoreramCustomBundle:Address",
 *      name  = "address"
 * )
 * @Entiy(
 *      class = "MmoreramCustomBundle:User",
 *      name  = "user",
 *      setters = {
 *          "setAddress": "address"
 *      }
 * )
 */
public function indexAction(Address $address, User $user)
{
}
```

When `User` instance is built, method `setAddress` is called using as parameter
the new `Address` instance.


## @Form

Provides form injection in your controller actions. This annotation only needs
a name to be defined in, where you must define namespace where your form is
placed.

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

> By default, if `name` option is not set, the generated object will be placed
> in a parameter named `$form`. This behaviour can be configured using
> `default_name` in configuration.

You can not just define your Type location using the namespace, in which case
a new AbstractType element will be created. but you can also define it using
service alias, in which case this bundle will return an instance using Symfony
DI.

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

This annotation allows you to not only create an instance of FormType, but
also allows you to inject a From object or a FormView object

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

You can also, using [SensioFrameworkExtraBundle][1]'s [ParamConverter][2],
create a Form object with an previously created entity. you can define this
entity using `entity` parameter.

``` php
<?php

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Mmoreram\ControllerExtraBundle\Annotation\Form as AnnotationForm;
use Symfony\Component\Form\Form;

/**
 * Simple controller method
 *
 * @Route(
 *      path = "/user/{id}",
 *      name = "view_user"
 * )
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

To handle current request, you can set `handleRequest` to true. By default
this value is set to `false`


``` php
<?php

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Mmoreram\ControllerExtraBundle\Annotation\Form as AnnotationForm;
use Symfony\Component\Form\Form;

/**
 * Simple controller method
 *
 * @Route(
 *      path = "/user/{id}",
 *      name = "view_user"
 * )
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

You can also add as a method parameter if the form is valid, using `validate`
setting. Annotation will place result of `$form->isValid()` in specified method
argument.

``` php
<?php

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Mmoreram\ControllerExtraBundle\Annotation\Form as AnnotationForm;
use Symfony\Component\Form\Form;

/**
 * Simple controller method
 *
 * @Route(
 *      path = "/user/{id}",
 *      name = "view_user"
 * )
 * @ParamConverter("user", class="MmoreramCustomBundle:User")
 * @AnnotationForm(
 *      class         = "user_type",
 *      entity        = "user"
 *      handleRequest = true,
 *      name          = "userForm",
 *      validate      = "isValid",
 * )
 */
public function indexAction(User $user, Form $userForm, $isValid)
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

Flush annotation allows you to flush entityManager at the end of request using
kernel.response event

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

If not otherwise specified, default Doctrine Manager will be flushed with this
annotation. You can overwrite default Mangager in your config.yml file

``` yml
controller_extra:
    flush:
        default_manager: my_custom_manager
```

You can also overwrite overwrite this value in every single Flush Annotation
instance defining `manager` value

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

> If multiple @Mmoreram\Flush are defined in same action, last instance will
> overwrite previous. Anyway just one instance should be defined.

## @Log

Log annotation allows you to log any plain message before or after controller
action execution

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

You can define the level of the message. You can define default one if none is
specified overwriting it in your `config.yml` file.

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

Several levels can be used, as defined in [Psr\Log\LoggerInterface][6]
interface

* @Mmoreram\Log::LVL_EMERG
* @Mmoreram\Log::LVL_CRIT
* @Mmoreram\Log::LVL_ERR
* @Mmoreram\Log::LVL_WARN
* @Mmoreram\Log::LVL_NOTICE
* @Mmoreram\Log::LVL_INFO
* @Mmoreram\Log::LVL_DEBUG
* @Mmoreram\Log::LVL_LOG


You can also define the execution of the log. You can define default one if
none is specified overwriting it in your `config.yml` file.

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

# Custom annotations

Using this bundle you can now create, in a very easy way, your own controller
annotation.

## Annotation

The annotation object. You need to define the fields your custom annotation
will contain. Must extends `Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation`
abstract class.

``` php
<?php

namespace My\Bundle\Annotation;

use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;

/**
 * Entity annotation driver
 *
 * @Annotation
 */
class MyCustomAnnotation extends Annotation
{

    /**
     * @var string
     *
     * Dummy field
     */
    private $field;
    
    
    /**
     * Get Dummy field
     *
     * @return string Dummy field
     */
    public function getField()
    {
        return $this->field;
    }
}
```

## Resolver

Once you have defined your own annotation, you have to resolve how this
annotation works in a controller. You can manage this using a Resolver. Must
extend `Mmoreram\ControllerExtraBundle\Resolver\Interfaces\AnnotationResolverInterface;`
abstract class.

``` php
<?php

namespace My\Bundle\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Mmoreram\ControllerExtraBundle\Resolver\Interfaces\AnnotationResolverInterface;
use Mmoreram\ControllerExtraBundle\Annotation\Abstracts\Annotation;

/**
 * MyCustomAnnotation Resolver
 */
class MyCustomAnnotationResolver implements AnnotationResolverInterface
{

    /**
     * Specific annotation evaluation.
     * This method MUST be implemented because is defined in the interface
     *
     * @param Request          $request    Request
     * @param Annotation       $annotation Annotation
     * @param ReflectionMethod $method     Method
     *
     * @return MyCustomAnnotationResolver self Object
     */
    public function evaluateAnnotation(
                                        Request $request, 
                                        Annotation $annotation, 
                                        ReflectionMethod $method )
    {
        /**
         * You can now manage your annotation.
         * You can access to its fields using public methods.
         * 
         * Annotation fields can be public and can be acceded directly,
         * but is better for testing to use getters; they can be mocked.
         */
        $field = $annotation->getField();
        
        /**
         * You can also access to existing method parameters.
         * 
         * Available parameters are:
         * 
         * # ParamConverter parameters ( See `resolver_priority` config value )
         * # All method defined parameters, included Request object if is set.
         */
        $entity = $request->attributes->get('entity');
        
        /**
         * And you can now place new elements in the controller action.
         * In this example we are creating new method parameter
         * called $myNewField with some value
         */
        $request->attributes->set(
            'myNewField',
            new $field()
        );
        
        return $this;
    }

}
```

This class will be defined as a service, so this method is computed just
before executing current controller. You can also subscribe to some kernel
events and do whatever you need to do ( You can check
`Mmoreram\ControllerExtraBundle\Resolver\LogAnnotationResolver` for some
examples.

## Definition

Once Resolver is done, we need to define our service as an Annotation
Resolver. We will use a custom `tag`.

``` yml
parameters:
    #
    # Resolvers
    #
    my.bundle.resolvers.my_custom_annotation_resolver.class: My\Bundle\Resolver\MyCustomAnnotationResolver

services:
    #
    # Resolvers
    #
    my.bundle.resolvers.my_custom_annotation_resolver:
        class: %my.bundle.resolvers.my_custom_annotation_resolver.class%
        tags:
            - { name: controller_extra.annotation }
```

## Registration

We need to register our annotation inside our application. We can just do it in
the `boot()` method of `bundle.php` file.

``` php
<?php

namespace My\Bundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * MyBundle
 */
class ControllerExtraBundle extends Bundle
{

    /**
     * Boots the Bundle.
     */
    public function boot()
    {
        $kernel = $this->container->get('kernel');

        AnnotationRegistry::registerFile($kernel
            ->locateResource("@MyBundle/Annotation/MyCustomAnnotation.php")
        );
    }
}
```

*Et voilà!*  We can now use our custom Annotation in our project controllers.

# Contributing

All code is Symfony2 Code formatted, so every pull request must validate phpcs
standards. You should read
[Symfony2 coding standards](http://symfony.com/doc/current/contributing/code/standards.html)
and install [this](https://github.com/opensky/Symfony2-coding-standard)
CodeSniffer to check all code is validated.

There is also a policy for contributing to this project. All pull request must
be all explained step by step, to make us more understandable and easier to
merge pull request. All new features must be tested with PHPUnit.

If you'd like to contribute, please read the [Contributing Code][3] part of the
documentation. If you're submitting a pull request, please follow the guidelines
in the [Submitting a Patch][4] section and use the [Pull Request Template][5].

[1]: https://github.com/sensiolabs/SensioFrameworkExtraBundle
[2]: http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
[3]: http://symfony.com/doc/current/contributing/code/index.html
[4]: http://symfony.com/doc/current/contributing/code/patches.html#check-list
[5]: http://symfony.com/doc/current/contributing/code/patches.html#make-a-pull-request
[6]: https://github.com/php-fig/log/blob/master/Psr/Log/LoggerInterface.php
