# Introduction

A Symfony2 developer tool to cope with the various ways of identifying classes, templates, etc.
Provides console commands to find the filepath for some class, template, bundle, ...

# Installation

Add this bundle to your project as Git submodule:

    git submodule add git://github.com/benoitpointet/LiipCodeBundle.git vendor/bundles/Liip/CodeBundle

Initialize the git submodule

    git submodule init
    git submodule update

Add its namespace to your autoloader:

    // app/autoload.php
    $loader->registerNamespaces(array(
        // ...
        'Liip' => __DIR__.'/../vendor/bundles',
        // ...
    ));

Add this bundle to your application kernel, as a development/test bundle:

    // app/AppKernel.php
    public function registerBundles()
    {
        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            // ...
            $bundles[] = new Liip\CodeBundle\LiipCodeBundle();
            // ...
        );
    }

Configure the code:edit command to work with your favorite editor:

    ;app/config/parameters.ini
    [parameters]
        ; ...
        liip.code.edit_command="vim -f"

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

Note that, in the case of a template, it does not need to exist:

    app/console code:path AcmeDemoBundle:Dummy:dummy.html.twig
    => @AcmeDemoBundle/Resources/views/Dummy/dummy.html.twig

... useful when you need to create a template and don't remember where to put it.

For resources other than templates, code:path is synonymous to code:locate.

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

You may also want to have a look at the container:debug command, which allows you to inspect services in a deeper manner.

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
Don't forget to configure CodeBundle to work with your favorite editor (see installation instructions).

Edit a twig template:

    app/console code:edit AcmeDemoBundle:Demo:hello.html.twig
    => locates and opens the template source file in editor

See code:locate instructions above for more infos.
