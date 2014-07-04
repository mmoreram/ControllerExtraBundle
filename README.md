ControllerExtra for Symfony2
=====

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/66e58cb8-cc5c-4899-8082-80cf23ef15af/mini.png)](https://insight.sensiolabs.com/projects/66e58cb8-cc5c-4899-8082-80cf23ef15af)
[![Build Status](https://travis-ci.org/mmoreram/ControllerExtraBundle.png?branch=master)](https://travis-ci.org/mmoreram/ControllerExtraBundle)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/mmoreram/ControllerExtraBundle/badges/quality-score.png?s=e960930a8cd10d62ec092248d14af620aa96ea9a)](https://scrutinizer-ci.com/g/mmoreram/ControllerExtraBundle/)
[![Latest Stable Version](https://poser.pugx.org/mmoreram/controller-extra-bundle/v/stable.png)](https://packagist.org/packages/mmoreram/controller-extra-bundle)
[![Latest Unstable Version](https://poser.pugx.org/mmoreram/controller-extra-bundle/v/unstable.png)](https://packagist.org/packages/mmoreram/controller-extra-bundle)
[![Dependency Status](https://www.versioneye.com/user/projects/52b09eefec137588e200007e/badge.png)](https://www.versioneye.com/user/projects/52b09eefec137588e200007e)

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
1. [Entity Provider](#entity-provider)
    * [By namespace](#by-namespace)
    * [By doctrine shortcut](#by-doctrine-shortcut)
    * [By parameter](#by-parameter)
    * [By factory](#by-factory)
1. [Bundle Annotations](#bundle-annotations)
    * [@Paginator](#paginator)
        * [Paginator Entity](#paginator-entity)
        * [Paginator Page](#paginator-page)
        * [Paginator Limit](#paginator-limit)
        * [Paginator OrderBy](#paginator-orderby)
        * [Paginator Wheres](#paginator-wheres)
        * [Paginator Left Joins](#paginator-left-joins)
        * [Paginator Inner Joins](#paginator-inner-joins)
        * [Paginator Not Nulls](#paginator-not-nulls)
        * [Paginator Example](#paginator-example)
    * [@Entity](#entity)
        * [Factory](#factory)
    * [@Form](#form)
    * [@Flush](#flush)
    * [@JsonResponse](#jsonresponse)
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
    factory:
        default_method: create
        default_static: true
    pagination:
        active: true
        default_name: paginator
        default_page: 1
        default_limit_per_page: 10
    entity:
        active: true
        default_name: entity
        default_manager: default
        default_persist: true
    form:
        active: true
        default_name: form
    flush:
        active: true
        default_manager: default
    json_response:
        active: true
        default_status: 200
        default_headers: []
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

# Entity provider

In some annotations, you can define an entity by several ways. This chapter is
about how you can define them.

## By namespace

You can define an entity using its namespace. A simple new `new()` be performed.

``` php
/**
 * Simple controller method
 *
 * @SomeAnnotation(
 *      class = "Mmoreram\CustomBundle\Entity\MyEntity",
 * )
 */
public function indexAction()
{
}
```

## By doctrine shortcut

You can define an entity using Doctrine shortcut notations. With this format
you should ensure that your Entities follow Symfony Bundle standards and your
entities are placed under `Entity/` folder.

``` php
/**
 * Simple controller method
 *
 * @SomeAnnotation(
 *      class = "MmoreramCustomBundle:MyEntity",
 * )
 */
public function indexAction()
{
}
```

## By parameter

You can define an entity using a simple config parameter. Some projects
use parameters to define all entity namespaces (To allow overriding). If you
define the entity with a parameter, this bundle will try to instance it
with a simple `new()` accessing directly to the container ParametersBag.

``` yml
parameters:

    #
    # Entities
    #
    my.bundle.entity.myentity: Mmoreram\CustomBundle\Entity\MyEntity
```

``` php
/**
 * Simple controller method
 *
 * @SomeAnnotation(
 *      class = "my.bundle.entity.myentity",
 * )
 */
public function indexAction()
{
}
```

## By factory

You can an entity using a factory class. This configuration have three values.

* factory - factory class
* method - Method to use when retrieving the object
* static - Method is static

You can define the factory with a simple namespace

``` php
/**
 * Simple controller method
 *
 * @SomeAnnotation(
 *      class = {
 *          "factory" = "Mmoreram\CustomBundle\Factory\MyEntityFactory",
 *          "method" = "create",
 *          "static" = true,
 *      },
 * )
 */
public function indexAction()
{
}
```

or with a service name

``` yml
parameters:

    #
    # Factories
    #
    my.bundle.factory.myentity_factory: Mmoreram\CustomBundle\Factory\MyEntityFactory
```

``` php
/**
 * Simple controller method
 *
 * @SomeAnnotation(
 *      class = {
 *          "factory" = my.bundle.factory.myentity_factory,
 *          "method" = "create",
 *          "static" = true,
 *      },
 * )
 */
public function indexAction()
{
}
```

If you do not define the `method`, default one will be used. You can
override this default value by defining new one in your config.yml. Same with
`static` value

``` yml
controller_extra:
    factory:
        default_method: create
        default_static: true
```


# Bundle annotations

This bundle provide a reduced but useful set of annotations for your controller

## @Paginator

Creates a Doctrine Paginator object, given a request and a configuration. This
annotation just injects into de controller a new
`Doctrine\ORM\Tools\Pagination\Pagination` instance ready to be iterated.

You can enable/disable this bundle by overriding `active` flag in configuration

``` yml
controller_extra:
    pagination:
        active: true
```

> By default, if `name` option is not set, the generated object will be placed
> in a parameter named `$paginator`. This behaviour can be configured using
> `default_name` in configuration.

This annotation can be configurated with these sections

### Paginator Entity

To create a new Pagination object you need to refer to an existing Entity. You
can check all available formats you can define it just reading the
[Entity Provider](#entity-provider) section.

``` php
<?php

use Doctrine\ORM\Tools\Pagination\Pagination;
use Mmoreram\ControllerExtraBundle\Annotation\Paginator;

/**
 * Simple controller method
 *
 * @Paginator(
 *      class = "MmoreramCustomBundle:User",
 * )
 */
public function indexAction(Pagination $pagination)
{
}
```

### Paginator page

You need to specify Paginator annotation the page to fetch. By default, if none
is specified, this bundle will use the default one defined in configuration. You
can override in `config.yml`

``` yml
controller_extra:
    pagination:
        default_page: 1
```

You can refer to an existing Request attribute using `%value%` format

``` php
/**
 * Simple controller method
 *
 * This Controller matches pattern /myroute/paginate/{pag}
 *
 * @Paginator(
 *      class = "MmoreramCustomBundle:User",
 *      page = "%pag%"
 * )
 */
public function indexAction(Pagination $pagination)
{
}
```

or you can hardcode the page to use.

``` php
/**
 * Simple controller method
 *
 * This Controller matches pattern /myroute/paginate/
 *
 * @Paginator(
 *      class = "MmoreramCustomBundle:User",
 *      page = 1
 * )
 */
public function indexAction(Pagination $pagination)
{
}
```

### Paginator limit

You need to specify Paginator annotation the limit to fetch. By default, if none
is specified, this bundle will use the default one defined in configuration. You
can override in `config.yml`

``` yml
controller_extra:
    pagination:
        default_limit_per_page: 10
```

You can refer to an existing Request attribute using `%value%` format

``` php
/**
 * Simple controller method
 *
 * This Controller matches pattern /myroute/paginate/{pag}/{limit}
 *
 * @Paginator(
 *      class = "MmoreramCustomBundle:User",
 *      page = "%pag%",
 *      limit = "%limit%"
 * )
 */
public function indexAction(Pagination $pagination)
{
}
```

or you can hardcode the page to use.

``` php
/**
 * Simple controller method
 *
 * This Controller matches pattern /myroute/paginate/
 *
 * @Paginator(
 *      class = "MmoreramCustomBundle:User",
 *      page = 1,
 *      limit = 10
 * )
 */
public function indexAction(Pagination $pagination)
{
}
```

### Paginator OrderBy

You can order your Pagination just defining the fields you want to orderBy and
the desired direction. The `orderBy` section must be defined as an array of
arrays, and each array should contain these positions:

* First position: Field
* Second position: Direction
* Third position: Custom direction map ***(optional)***

``` php
/**
 * Simple controller method
 *
 * @Paginator(
 *      class = "MmoreramCustomBundle:User",
 *      orderBy = {
 *          {"createdAt", "ASC"},
 *          {"updatedAt", "DESC"},
 *          {"id", 1, {
 *              0 => "ASC",
 *              1 => "DESC",
 *          }},
 *      }
 * )
 */
public function indexAction(Pagination $pagination)
{
}
```

With the third value you can define a map where to match your own direction
nomenclature with DQL one. DQL nomenclature just accept ASC for Ascendant and
DESC for Descendant.

This is very useful when you need to match a url format with the DQL one. You
can refer to an existing Request attribute using `%value%` format

``` php
/**
 * Simple controller method
 *
 * This Controller matches pattern /myroute/paginate/order/{field}/{direction}
 *
 * For example, some matchings...
 *
 * /myroute/paginate/order/id/1 -> ORDER BY id DESC
 * /myroute/paginate/order/enabled/0 - ORDER BY enabled ASC
 *
 * @Paginator(
 *      class = "MmoreramCustomBundle:User",
 *      orderBy = {
 *          {"createdAt", "ASC"},
 *          {"updatedAt", "DESC"},
 *          {"%field%", %direction%, {
 *              0 => "ASC",
 *              1 => "DESC",
 *          }},
 *      }
 * )
 */
public function indexAction(Pagination $pagination)
{
}
```

The order of the definitions will alter the order of the DQL query.

### Paginator Wheres

You can define some where statements in your Paginator. The `wheres` section
must be defined as an array of arrays, and each array should contain these
positions:

* First position: Field
* Second position: Operator ***=, <=, >...***
* Third position: Value to compare with

``` php
/**
 * Simple controller method
 *
 * @Paginator(
 *      class = "MmoreramCustomBundle:User",
 *      wheres = {
 *          {"enabled", "=", true},
 *          {"disabled", "=", false},
 *      }
 * )
 */
public function indexAction(Pagination $pagination)
{
}
```

### Paginator Not Nulls

You can also define some fields to not null. Is same as `wheres` section, but
specific for NULL assignments. The `noNulls` section must be defined as an array
of fields.

``` php
/**
 * Simple controller method
 *
 * @Paginator(
 *      class = "MmoreramCustomBundle:User",
 *      notNulls = {"enabled", "deleted"}
 * )
 */
public function indexAction(Pagination $pagination)
{
}
```


### Paginator Left Join

You can do some left joins in this section. The `leftJoins` section must be
defined as an array of array, where each array can have these fields:

* First field: Entity relation (x.Address)
* Second field: Relation identifier (a)
* Third field: If true, this relation is added in select group. Otherwise, wont
be loaded until its request ***(optional)***

``` php
/**
 * Simple controller method
 *
 * @Paginator(
 *      class = "MmoreramCustomBundle:User",
 *      leftJoins = {
 *          {"x User", "u", true},
 *          {"u Address", "a", true},
 *          {"x.Cart", "c"},
 *      }
 * )
 */
public function indexAction(Pagination $pagination)
{
}
```

### Paginator Inner Join

You can do some left joins in this section. The `innerJoins` section must be
defined as an array of array, where each array can have these fields:

* First field: Entity relation (x.Address)
* Second field: Relation identifier (a)
* Third field: If true, this relation is added in select group. Otherwise, wont
be loaded until its request ***(optional)***

``` php
/**
 * Simple controller method
 *
 * @Paginator(
 *      class = "MmoreramCustomBundle:User",
 *      innerJoins = {
 *          {"x User", "u", true},
 *          {"u Address", "a", true},
 *          {"x.Cart", "c"},
 *      }
 * )
 */
public function indexAction(Pagination $pagination)
{
}
```

### Paginator Example

This is a completed example and its DQL resolution

``` php
/**
 * Simple controller method
 *
 * This Controller matches pattern /paginate/nb/{limit}/{page}
 *
 * Where:
 *
 * * limit = 10
 * * page = 1
 *
 * @Paginator(
 *      class = (
 *          factoryClass = "Mmoreram\ControllerExtraBundle\Factory\EntityFactory",
 *          factoryMethod = "create",
 *          factoryStatic = true
 *      ),
 *      page = "%page%",
 *      limit = "%limit%",
 *      orderBy = {
 *          { "createdAt", "ASC" },
 *          { "id", "ASC" }
 *      },
 *      wheres = {
 *          { "enabled" , "=", true }
 *      },
 *      leftJoins = {
 *          { "x.relation", "r" },
 *          { "x.relation2", "r2" },
 *          { "x.relation5", "r5", true },
 *      },
 *      innerJoins = {
 *          { "x.relation3", "r3" },
 *          { "x.relation4", "r4", true },
 *      },
 *      notNulls = {
 *          "address1",
 *          "address2",
 *      }
 * )
 */
public function indexAction(Pagination $pagination)
{
}
```

The DQL generated by this annotation is

``` sql
    SELECT x, r4, r5
    FROM Mmoreram\\ControllerExtraBundle\\Tests\\FakeBundle\\Entity\\Fake x

    INNER JOIN x.relation3 r3
    INNER JOIN x.relation4 r4

    LEFT JOIN x.relation r
    LEFT JOIN x.relation2 r2
    LEFT JOIN x.relation5 r5

    WHERE enabled = ?where0
    AND x.address1 IS NOT NULL
    AND x.address2 IS NOT NULL

    ORDER BY createdAt ASC, id ASC
```

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
 * @Entity(
 *      class = "MmoreramCustomBundle:Address",
 *      name  = "address"
 * )
 * @Entity(
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

New entities are just created with a simple `new()`, so they are not persisted.
By default, they will be persisted using `default` manager, but you can disable
this feature using `persist` option.

You can also change manager using `manager` option.

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\Entity;
use Mmoreram\ControllerExtraBundle\Entity\User;

/**
 * Simple controller method
 *
 * @Entity(
 *      class = "MmoreramCustomBundle:User",
 *      name  = "user",
 *      persist = false,
 *      manager = 'my_own_manager'
 * )
 */
public function indexAction(User $user)
{
}
```

If you want to change default manager in all annotation instances, you should
overwrite bundle parameter.

``` yml
controller_extra:
    entity:
        default_manager: my_own_manager
```

### Factory

Following some Domain Driven Development (DDD) principles, an entity should be
always created using Factories. This is because, when working with interfaces,
just replacing the factory namespace, desired Entity is created.

ControllerExtraBundle Entity annotation enables you to create entities using a
factory class.

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\Entity;

/**
 * Simple controller method
 *
 * @Entity(
 *      factoryClass = "Mmoreram\ControllerExtraBundle\Factory\EntityFactory",
 *      factoryMethod = "create"
 * )
 */
public function indexAction(User $user)
{
}
```

In this case, `EntityFactory` will be instanced and `create` will be called to
retrieve entity instance.

You can also call your method as an static method, so Factory will not be
instanced.

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\Entity;

/**
 * Simple controller method
 *
 * @Entity(
 *      factoryClass = "Mmoreram\ControllerExtraBundle\Factory\EntityFactory",
 *      factoryMethod = "create",
 *      factoryStatic = true
 * )
 */
public function indexAction(User $user)
{
}
```

If you want to define your Factory as a service, with the possibility of
overriding namespace, you can simply define service name. All other options have
the same behaviour.

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\Entity;

/**
 * Simple controller method
 *
 * @Entity(
 *      factoryClass = "my_factory_service",
 *      factoryMethod = "create",
 * )
 */
public function indexAction(User $user)
{
}
```

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
use Symfony\Component\Form\Form;

use Mmoreram\ControllerExtraBundle\Annotation\Form as AnnotationForm;
use Mmoreram\ControllerExtraBundle\Entity\User;

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
use Symfony\Component\Form\Form;

use Mmoreram\ControllerExtraBundle\Annotation\Form as AnnotationForm;
use Mmoreram\ControllerExtraBundle\Entity\User;

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
use Symfony\Component\Form\Form;

use Mmoreram\ControllerExtraBundle\Annotation\Form as AnnotationForm;
use Mmoreram\ControllerExtraBundle\Entity\User;

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

use Symfony\Component\Form\FormView;

use Mmoreram\ControllerExtraBundle\Annotation\Form;

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
 *      manager = "my_own_manager"
 * )
 */
public function indexAction()
{
}
```

If you want to change default manager in all annotation instances, you should
overwrite bundle parameter.

``` yml
controller_extra:
    flush:
        default_manager: my_own_manager
```

If any parameter is set, annotation will flush all. If you only need to flush
one or many entities, you can define explicitly which entity must be flushed.

``` php
<?php

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Mmoreram\ControllerExtraBundle\Annotation\Flush;
use Mmoreram\ControllerExtraBundle\Entity\User;

/**
 * Simple controller method
 *
 * @ParamConverter("user", class="MmoreramCustomBundle:User")
 * @Flush(
 *      entity = "user"
 * )
 */
public function indexAction(User $user)
{
}
```

You can also define a set of entities to flush

``` php
<?php

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Mmoreram\ControllerExtraBundle\Annotation\Flush;
use Mmoreram\ControllerExtraBundle\Entity\Address;
use Mmoreram\ControllerExtraBundle\Entity\User;

/**
 * Simple controller method
 *
 * @ParamConverter("user", class="MmoreramCustomBundle:User")
 * @ParamConverter("address", class="MmoreramCustomBundle:Address")
 * @Flush(
 *      entity = {
 *          "user", 
 *          "address"
 *      }
 * )
 */
public function indexAction(User $user, Address $address)
{
}
```

> If multiple @Mmoreram\Flush are defined in same action, last instance will
> overwrite previous. Anyway just one instance should be defined.

## @JsonResponse

JsonResponse annotation allows you to create a 
`Symfony\Component\HttpFoundation\JsonResponse` object, given a simple
controller return value. 

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\JsonResponse;

/**
 * Simple controller method
 *
 * @JsonResponse
 */
public function indexAction(User $user, Address $address)
{
    return array(
        'This is my response'
    );
}
```

By default, JsonResponse is created using default `status` and `headers` defined
in bundle parameters. You can overwrite them.

``` yml
controller_extra:
    json_response:
        default_status: 403
        default_headers:
            "User-Agent": "Googlebot/2.1"
```

You can also overwrite these values in each `@JsonResponse` annotation.

``` php
<?php

use Mmoreram\ControllerExtraBundle\Annotation\JsonResponse;

/**
 * Simple controller method
 *
 * @JsonResponse(
 *      status = 403,
 *      headers = {
 *          "User-Agent": "Googlebot/2.1"
 *      }
 * )
 */
public function indexAction(User $user, Address $address)
{
    return array(
        'This is my response'
    );
}
```

> If multiple @Mmoreram\JsonResponse are defined in same action, last instance 
> will overwrite previous. Anyway just one instance should be defined.

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

use Mmoreram\ControllerExtraBundle\Annotation\Log;

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
    public $field;
    
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
        ReflectionMethod $method
    )
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
    my.bundle.resolver.my_custom_annotation_resolver.class: My\Bundle\Resolver\MyCustomAnnotationResolver

services:
    #
    # Resolvers
    #
    my.bundle.resolver.my_custom_annotation_resolver:
        class: %my.bundle.resolver.my_custom_annotation_resolver.class%
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


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/mmoreram/controllerextrabundle/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

