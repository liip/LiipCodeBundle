<?php

namespace Bp\CodeBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;

class DefaultController extends ContainerAware
{
    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

   /*
    * Returns the path for a given template lookup (logical name)
    */
    public function pathAction($lookup)
    {
        // access services
        $parser = $this->container->get('templating.name_parser');
        $locator = $this->container->get('file_locator');

        // template logicalName to symfony path
        $template_reference = $parser->parse($lookup);
        $sf_path = $template_reference->getPath();
        $file_path = $locator->locate($sf_path);

        return $file_path;
    }
}
