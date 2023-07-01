<?php


namespace utils\graph;


class Graph
{
    private $edges = [];

    public function addNode($node)
    {
        if ($node instanceof GraphNode) {
            $node = $node->getName();
        }

        $this->edges[$node] = [];
    }

    public function addEdge($node1, $node2, $width)
    {
        $this->edges[(string)$node1][(string)$node2] = [$node2, $node1, $width];
        $this->edges[(string)$node2][(string)$node1] = [$node1, $node2, $width];
    }

    public function getNode(): iterable
    {
        return array_keys($this->edges);
    }

    public function getEdge($node1): iterable
    {
        $key = (($node1 instanceof GraphNode) ? $node1->getName() : $node1);
        foreach ($this->edges[$key] as $node2 => $width) {
            yield $node2 => $width;
        }
    }
}