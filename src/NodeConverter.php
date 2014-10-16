<?php

namespace DTL\PHPCR\Generator;

use PHPCR\SessionInterface;
use PHPCR\NodeInterface;
use PHPCR\Util\NodeHelper;
use PHPCR\PropertyType;

/**
 * Convert the node builder into a phpcr tree
 */
class NodeConverter
{
    protected $session;
    protected $count = 0;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function convert(NodeBuilder $builder)
    {
        $rootNode = $this->session->getRootNode();
        $this->walkNode($builder, $rootNode);
    }

    private function walkNode(NodeBuilder $builder, NodeInterface $parentNode)
    {
        for ($i = $builder->getRangeStart(); $i <= $builder->getRangeEnd(); $i++) {
            $name = str_replace(NodeBuilder::ITERATION_TOKEN, $i, $builder->getName());

            $this->count++;
            $node = $parentNode->addNode($name, $builder->getNodeTypeName());

            foreach ($builder->getProperties() as $property) {
                list($name, $value, $type) = $property;
                $node->setProperty($name, $value, PropertyType::valueFromName($type));
            }

            foreach ($builder->getNodes() as $childNodeBuilder) {
                $this->walkNode($childNodeBuilder, $node);
            }
        }
    }

    public function getCount() 
    {
        return $this->count;
    }
    
}
