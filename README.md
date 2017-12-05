UNMAINTAINED
============

This bundle is no longer maintained. Feel free to fork it if needed.

# Introduction

A set of Symfony2 console commands to help developers deal with the various ways of identifying classes, templates,
bundles, services, etc. Provides console commands to find their file path or class, as well as editor shortcuts.

*  `code:path` outputs the symfony path corresponding to a class, service, template, etc.
*  `code:locate` finds the file corresponding to a class, service, template, etc.
*  `code:class` outputs the class of a service.
*  `code:edit` edits the file corresponding to a class, service, template, etc.
*  `code:view` displays the file corresponding to a class, service, template, etc.

## Installation ##

Add the following code to your ```composer.json``` file:

    "require": {
        ..
        "liip/code-bundle": "dev-master"
    },

And then run the Composer update command:

    $ php composer.phar update liip/code-bundle

Then register the bundle in the `AppKernel.php` file:

    public function registerBundles()
    {
        $bundles = array(
            ...
            new Liip\CodeBundle\LiipCodeBundle(),
            ...
        );

        return $bundles;
    }

Configure the `code:edit` and `code:view` console command to work with your favorite editor:

```yml
liip_code:
    edit_command: "vim -f"
    view_command: "vim -f"
```

In this example, the `app/console code:edit some_resource_name` command will indeed lookup the resource and execute `vim -f /path/to/the/corresponding/file`.

Type `app/console` and check that new console commands are available of the form `code:*`

This bundle currently defines no routes, nor does it require configuration.

# Usage

## Common options

The option `--type=(class|service|template)` can be used in case of ambiguous lookup:

    # templating engine service name is ambiguous, the following triggers an AmbiguousLookupException
    app/console code:locate templating

    # add type option to resolve ambiguity
    app/console code:locate templating --type=service

## code:path

Returns the "symfony path" of something based on its "name".

Symfony path for a template:

    app/console code:path AcmeDemoBundle:Demo:hello.html.twig
    => @AcmeDemoBundle/Resources/views/Demo/hello.html.twig

Note that, in the case of a template, it does not need to exist:

    app/console code:path AcmeDemoBundle:Dummy:dummy.html.twig
    => @AcmeDemoBundle/Resources/views/Dummy/dummy.html.twig

... useful when you need to create a template and don't remember where to put it.

For resources other than templates, `code:path` is synonymous to code:locate.

## code:locate

Returns the "absolute filepath" of something.

### Locate by name:

Locate a twig template by name:

    app/console code:locate AcmeDemoBundle:Demo:hello.html.twig
    => /path/to/symfony2-root/src/Acme/DemoBundle/Resources/views/Demo/hello.html.twig

Locate a class by name:

    app/console code:locate "Acme\DemoBundle\ControllerListener"
    => /path/to/symfony2-root/src/Acme/DemoBundle/ControllerListener.php

Note that the class name must be wrapped in quotes.
Currently only classes managed by the Symfony2 autoloader will be picked.

Locate a service by id:

    app/console code:locate acme.demo.listener
    => /path/to/symfony2-root/src/Acme/DemoBundle/ControllerListener.php

You may also want to have a look at the `container:debug` console command, which allows you to inspect services in a deeper manner.

### Locate by "symfony path"

Locate a bundle:

    app/console code:locate @AcmeDemoBundle
    => /path/to/symfony2-root/src/Acme/DemoBundle

Locate a directory:

    app/console code:locate @AcmeDemoBundle/Resources/views
    => /path/to/symfony2-root/src/Acme/DemoBundle/Resources/views

Locate a file:

    app/console code:locate @AcmeDemoBundle/Resources/views/Demo/hello.html.twig
    => /path/to/symfony2-root/src/Acme/DemoBundle/Resources/views/Demo/hello.html.twig

## code:class

Obtain the class of a service:

    app/console code:class acme.demo.listener
    => Acme\DemoBundle\ControllerListener

## code:edit

Locates and edits the file corresponding to a class, template, etc.
Don't forget to configure CodeBundle to work with your favorite editor (see installation instructions).

Edit a twig template:

    app/console code:edit AcmeDemoBundle:Demo:hello.html.twig
    => locates and opens the template source file in editor

See code:locate instructions above for more infos.

## code:view

Locates and displays the file corresponding to a class, template, etc.
Works exactly the same as the `code:edit` console command, still handy if you want to make sure you don't mess around while browsing code.
