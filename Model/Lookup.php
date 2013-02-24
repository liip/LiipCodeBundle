<?php

/*
 * This file is part of the Liip/CodeBundle
 *
 * (c) 2011 Benoit Pointet <benoit.pointet@liip.ch>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Liip\CodeBundle\Model;

use Liip\CodeBundle\Exception\AmbiguousLookupException;

use Symfony\Component\DependencyInjection\ContainerInterface;

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
    protected $lookupType;
    protected $resourceType;
    protected $resourceTypes = array(
        Lookup::RT_TEMPLATE,
        Lookup::RT_CLASS,
        Lookup::RT_SERVICE,
    );
    protected $container;

    /**
     * Constructor
     *
     * @param string $lookup Some lookup string: either a name, id, ...
     * @param string $resourceType Type of the resource looked up
     * @param string $container
     */
    public function __construct($lookup, $resourceType, ContainerInterface $container)
    {
        $this->lookup = $lookup;
        $this->container = $container;

        $this->typify($resourceType);
    }
    /*
     * @return allowed type option values
     */
    public function getTypeOptionSyntax()
    {
        return sprintf('(%s)', implode($this->resourceTypes, '|'));
    }

    /*
     * Identifies the types of the lookup and corresponding resource
     * @param string $resourceType optional resource type indication
     */
    public function typify($resourceType = null)
    {
        // identify symfony paths of the form @MyBundle/../..
        if ($this->lookup[0] === '@') {
            $this->lookupType = Lookup::LT_PATH;
            return;
        }

        if ($resourceType) {
            if (!in_array($resourceType, $this->resourceTypes)) {
                throw new AmbiguousLookupException(sprintf("'%s' is not a valid resource type indication.\nUse '--type=%s' option to indicate the looked up resource type.", $this->getTypeOptionSyntax(), $this->lookup));
            }

            $this->lookupType = Lookup::LT_NAME;
            $this->resourceType = $resourceType;
            return;
        }

        // identify class names of the form \Path\To\My\Class
        if (strpos($this->lookup, '\\')) {
            $this->lookupType = Lookup::LT_NAME;
            $this->resourceType = Lookup::RT_CLASS;
            return;
        }

        // identify template names of the form MyBundle:folder:template
        if (strpos($this->lookup, ':')) {
            $this->lookupType = Lookup::LT_NAME;
            $this->resourceType = Lookup::RT_TEMPLATE;
            return;
        }

        // identify service names of the form my.service.name.space
        if (strpos($this->lookup, '.')) {
            $this->lookupType = Lookup::LT_NAME;
            $this->resourceType = Lookup::RT_SERVICE;
            return;
        }

        throw new AmbiguousLookupException(sprintf("No type could be determined for '%s'.\nUse '--type=%s' option to indicate the looked up resource type.", $this->getTypeOptionSyntax(), $this->lookup));
    }

    /*
     * return the class namespace/name
     */
    public function getClass()
    {
        $class = null;

        if ($this->lookupType == Lookup::LT_PATH) {
            // TODO handle this case
        }

        if ($this->lookupType == Lookup::LT_NAME) {
            if ($this->resourceType == Lookup::RT_CLASS) {
                $class = $this->lookup;
            }

            if ($this->resourceType == Lookup::RT_SERVICE) {
                $class = $this->getServiceClass($this->lookup);
            }
        }

        if (! $class) {
           throw new \InvalidArgumentException(sprintf('Unable to find class of %s "%s"', $this->resourceType, $this->lookup));
        }

        return $class;

    }

    /*
     * param $name the service id
     * @return class of a service
     */
    protected function getServiceClass($name)
    {
        // access service
        return get_class($this->container->get($name));
    }

    /*
     * param $name the template logical name
     * @return string path
     */
    protected function getTemplatePath($name)
    {
        // access services
        $parser = $this->container->get('templating.name_parser');

        // map template logicalName to symfony resource path
        return $parser->parse($name)->getPath();
    }

    /*
     * param $name the class name
     * @return string path of class
     */
    protected function getClassPath($name)
    {
        $ref = new \ReflectionClass($name);
        return $ref->getFileName();
    }

    /*
     * param $name the service id
     * @return string path of a service
     */
    protected function getServicePath($name)
    {
        return $this->getClassPath($this->getServiceClass($name));
    }

    /*
     * @return string path of looked up resource
     */
    public function getPath()
    {
        $path = null;

        if ($this->lookupType == Lookup::LT_PATH) {
            $path = $this->lookup;
        }

        if ($this->lookupType == Lookup::LT_NAME) {
            if ($this->resourceType == Lookup::RT_TEMPLATE) {
                $path = $this->getTemplatePath($this->lookup);
            }

            if ($this->resourceType == Lookup::RT_CLASS) {
                $path = $this->getClassPath($this->lookup);
            }

            if ($this->resourceType == Lookup::RT_SERVICE) {
                $path = $this->getServicePath($this->lookup);
            }
        }

        if (! $path) {
           throw new \InvalidArgumentException(sprintf('Unable to find file of %s "%s"', $this->resourceType, $this->lookup));
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
        return $locator->locate($path);
    }
}
