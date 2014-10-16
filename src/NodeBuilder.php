<?php

namespace DTL\PHPCR\Generator;

class NodeBuilder
{
    const ITERATION_TOKEN = '%__iteration_token__%';

    private $name;
    private $nodeTypeName;
    private $properties = array();
    private $nodes = array();
    private $parent;
    private $rangeStart = 1;
    private $rangeEnd = 1;

    public function __construct($name, $nodeTypeName = 'nt:unstructured', $parent = null)
    {
        $this->name = $this->parseName($name);
        $this->nodeTypeName = $nodeTypeName;
        $this->parent = $parent;
    }

    public function property($name, $value, $type = 'undefined')
    {
        $this->properties[] = array($name, $value, $type);
        return $this;
    }

    public function node($name, $nodeTypeName = 'nt:unstructured')
    {
        $node = new NodeBuilder($name, $nodeTypeName, $this);
        $this->nodes[] = $node;

        return $node;
    }

    public function end()
    {
        if (null === $this->parent) {
            throw new \Exception(sprintf(
                'Cannot call end() on a root node "%s"', $this->name
            ));
        }

        return $this->parent;
    }

    public function getName() 
    {
        return $this->name;
    }

    public function getProperties() 
    {
        return $this->properties;
    }

    public function getNodeTypeName() 
    {
        return $this->nodeTypeName;
    }

    public function getNodes() 
    {
        return $this->nodes;
    }

    public function getRangeStart() 
    {
        return $this->rangeStart;
    }

    public function getRangeEnd() 
    {
        return $this->rangeEnd;
    }

    private function parseName($name)
    {
        preg_match('{\[([0-9]+)-([0-9]+)\]}', $name, $matches);

        if (empty($matches)) {
            return $name;
        }

        $name = str_replace($matches[0], self::ITERATION_TOKEN, $name);
        $this->rangeStart = $matches[1];
        $this->rangeEnd = $matches[2];

        return $name;
    }
}
