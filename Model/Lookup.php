<?php

namespace Liip\CodeBundle\Model;

/*
 * A resource lookup
 */
class Lookup
{
    const LT_PATH = 'path';
    const LT_NAME = 'name';
    const RT_TEMPLATE = 'template';
    const RT_CLASS = 'class';
    const RT_SERVICE = 'service';

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
            return;
        }

        // identify class names of the form \Path\To\My\Class
        if (strpos($this->lookup, '\\')) {
            $this->lookup_type = Lookup::LT_NAME;
            $this->resource_type = Lookup::RT_CLASS;
            return;
        }

        // identify template names of the form MyBundle:folder:template
        if (strpos($this->lookup, ':')) {
            $this->lookup_type = Lookup::LT_NAME;
            $this->resource_type = Lookup::RT_TEMPLATE;
            return;
        }

        // identify service names of the form my.service.name.space
        if (strpos($this->lookup, '.')) {
            $this->lookup_type = Lookup::LT_NAME;
            $this->resource_type = Lookup::RT_SERVICE;
            return;
        }
    }

    /*
     * param $name the template logical name
     * @return string path
     */
    public function getTemplatePath($name)
    {
        // access services
        $parser = $this->container->get('templating.name_parser');

        // map template logicalName to symfony resource path
        $template_reference = $parser->parse($name);
        $path = $template_reference->getPath();

        return $path;
    }

    /*
     * param $name the class name
     * @return string path of class
     */
    public function getClassPath($name)
    {
        // access autoloader
        $loaders = spl_autoload_functions();
        $loader = $loaders[0][0];

        $path = $loader->findFile($name);

        return $path;
    }

    /*
     * param $name the service id
     * @return string path of a service
     */
    public function getServicePath($name)
    {
        // access service
        $service = $this->container->get($name);
        $class = get_class($service);
        $path = $this->getClassPath($class);

        return $path;
    }

    /*
     * @return string path of looked up resource
     */
    public function getPath()
    {
        $path = null;

        if ($this->lookup_type == Lookup::LT_PATH) {
            $path = $this->lookup;
        }

        if ($this->lookup_type == Lookup::LT_NAME) {

            if ($this->resource_type == Lookup::RT_TEMPLATE) {
                $path = $this->getTemplatePath($this->lookup);
            }

            if ($this->resource_type == Lookup::RT_CLASS) {
                $path = $this->getClassPath($this->lookup);
            }

            if ($this->resource_type == Lookup::RT_SERVICE) {
                $path = $this->getServicePath($this->lookup);
            }

        }

        if (! $path) {
           throw new \InvalidArgumentException(sprintf('Unable to find file of %s "%s"', $this->resource_type, $this->lookup));
        }

        return $path;
    }

    /*
     * @return string its file system absolute path
     */
    public function getFilePath()
    {
        // get path
        $path = $this->getPath();

        // access services
        $locator = $this->container->get('file_locator');

        // map path to absolute filepath
        $file_path = $locator->locate($path);
        return $file_path;
    }
}
