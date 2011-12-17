# Introduction

A Symfony2 developer tool to cope with the various ways of identifying classes, templates, etc.
Provides console commands to find the filepath for some class, template, bundle, ...

# Installation

1. Add this bundle to your project as Git submodule:

    $ git submodule add git://github.com/benoitpointet/BpCodeBundle.git vendor/bundles/Bp/CodeBundle

2. Initialize the git submodule

    $ git submodule init
    $ git submodule update

3. Add its namespace to your autoloader:

    // app/autoload.php
    $loader->registerNamespaces(array(
        'Bp' => __DIR__.'/../vendor/bundles',
        // your other namespaces
    ));

4. Add this bundle to your application's kernel:

    // application/ApplicationKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Bp\CodeBundle\BpCodeBundle(),
            // ...
        );
    }

5. For now, this bundle defines no routes, nor does it require configuration.

# Usage

This bundles provides several Symfony2 console commands:

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


## code:path

Returns the "symfony path" of something based on its "name".

Symfony path for a template:

    app/console code:path AcmeDemoBundle:Demo:hello.html.twig
    => @AcmeDemoBundle/Resources/views/Demo/hello.html.twig

Note that the template does not need to exist:

    app/console code:path AcmeDemoBundle:Dummy:dummy.html.twig
    => @AcmeDemoBundle/Resources/views/Dummy/dummy.html.twig

... useful when you need to create a template and don't remember where to put it.
