# Introduction

A Symfony2 developer tool to cope with the various ways of identifying classes, templates, etc.
Provides console commands to find the filepath for some class, template, bundle, ...

# Installation

Add this bundle to your project as Git submodule:

    git submodule add git://github.com/benoitpointet/BpCodeBundle.git vendor/bundles/Bp/CodeBundle

Initialize the git submodule

    git submodule init
    git submodule update

Add its namespace to your autoloader:

    // app/autoload.php
    $loader->registerNamespaces(array(
        // ...
        'Bp' => __DIR__.'/../vendor/bundles',
        // ...
    ));

Add this bundle to your application kernel, as a development/test bundle:

    // app/AppKernel.php
    public function registerBundles()
    {
        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            // ...
            $bundles[] = new Bp\CodeBundle\BpCodeBundle();
            // ...
        );
    }

Configure the code:edit command to work with your favorite editor:

    ;app/config/parameters.ini
    [parameters]
        ; ...
        bp_code.edit_command="vim -f"

Type `app/console` and check that new commands are available of the form `code:*`

This bundle currently defines no routes, nor does it require configuration.

# Usage

This bundles provides several Symfony2 console commands:

*  __code:path__ gets the symfony path corresponding to a class, template, etc.
*  __code:locate__ finds the file corresponding to a class, template, etc.
*  __code:edit__ edits the file corresponding to a class, template, etc.

## code:path

Returns the "symfony path" of something based on its "name".

Symfony path for a template:

    app/console code:path AcmeDemoBundle:Demo:hello.html.twig
    => @AcmeDemoBundle/Resources/views/Demo/hello.html.twig

Note that the template does not need to exist:

    app/console code:path AcmeDemoBundle:Dummy:dummy.html.twig
    => @AcmeDemoBundle/Resources/views/Dummy/dummy.html.twig

... useful when you need to create a template and don't remember where to put it.

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

## code:edit

Locates and edits the file corresponding to a class, template, etc.

