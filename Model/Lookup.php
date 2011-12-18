<?php

namespace Bp\CodeBundle\Model;

/*
 * A resource lookup
 */
class Lookup
{
    const LT_PATH = 'path';
    const LT_NAME = 'name';
    const RT_TEMPLATE = 'template';
    const RT_CLASS = 'class';

    protected $lookup;
    protected $lookup_type;
    protected $resource_type;
    protected $container;

    /**
     * Constructor
     *
     * @param string $lookup
     */
    public function __construct($lookup, $container)
    {
        $this->lookup = $lookup;
        $this->container = $container;
        $this->typify();
    }

    /*
     * Identifies the types of the lookup and corresponding resource
     */
    public function typify()
    {
        // identify symfony paths of the form @MyBundle/../..
        if ($this->lookup[0] === '@') {
            $this->lookup_type = Lookup::LT_PATH;
        }

        // identify class names of the form \Path\To\My\Class
        if (strpos($this->lookup, '\\')) {
            $this->lookup_type = Lookup::LT_NAME;
            $this->resource_type = Lookup::RT_CLASS;
        }

        // identify template names of the form MyBundle:folder:template
        if (strpos($this->lookup, ':')) {
            $this->lookup_type = Lookup::LT_NAME;
            $this->resource_type = Lookup::RT_TEMPLATE;
        }
    }

    /*
     * @return string path of template resource
     */
    public function getTemplatePath()
    {
        // access services
        $parser = $this->container->get('templating.name_parser');

        // map template logicalName to symfony resource path
        $template_reference = $parser->parse($this->lookup);
        $path = $template_reference->getPath();

        return $path;
    }

    /*
     * @return string path of class
     */
    public function getClassPath()
    {
        // access autoloader
        $loaders = spl_autoload_functions();
        $loader = $loaders[0][0];

        $path = $loader->findFile($this->lookup);

        return $path;
    }

    /*
     * @return string path of looked up resource
     * TODO extend to other resources that templates
     */
    public function getPath()
    {
        $path = null;

        if ($this->lookup_type == Lookup::LT_PATH) {
            $path = $this->lookup;
        }

        if ($this->lookup_type == Lookup::LT_NAME) {

            if ($this->resource_type == Lookup::RT_TEMPLATE) {
                $path = $this->getTemplatePath();
            }

            if ($this->resource_type == Lookup::RT_CLASS) {
                $path = $this->getClassPath();
            }

        }

        return $path;
    }

    /*
     * @return string its file system absolute path
     */
    public function getFilePath()
    {
        // access services
        $locator = $this->container->get('file_locator');

        // map template logicalName to symfony path
        $file_path = $locator->locate($this->getPath());
        return $file_path;
    }
}
