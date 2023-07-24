<?php


namespace utils\graph;

/**
 * Class GraphNode
 * @package utils\graph
 * @packages helpers
 */
class GraphNode
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->name;
    }


}