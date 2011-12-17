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
    * @param string $lookup a template name
    * @return string its symfony resource path
    * TODO extend to other resources
    */
    public function resourcePathAction($lookup)
    {
        // access services
        $parser = $this->container->get('templating.name_parser');

        // map template logicalName to symfony resource path
        $template_reference = $parser->parse($lookup);
        $path = $template_reference->getPath();

        return $path;
    }

   /*
    * @param string $resource_path a symfony resource path (@bundle/../.. path pattern)
    * @return string its file system absolute path
    */
    public function resourceLocateAction($resource_path)
    {
        // access services
        $locator = $this->container->get('file_locator');

        // template logicalName to symfony path
        $file_path = $locator->locate($resource_path);
        return $file_path;
    }
}
