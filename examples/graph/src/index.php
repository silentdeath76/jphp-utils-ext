<?php
use utils\graph\{Graph, GraphNode};

$graph = new Graph();
$graph->addNode('Root');

$fireBall = new GraphNode("Fire ball");
$graph->addNode($fireBall);

$fireStorm = new GraphNode("Fire storm");
$graph->addNode($fireStorm);

$fireApocalypse = new GraphNode("Fire apocalypse");
$graph->addNode($fireApocalypse);

$graph->addEdge('Root', $fireBall, 1);
$graph->addEdge($fireBall, $fireStorm, 1);
$graph->addEdge($fireStorm, $fireApocalypse, 2);



foreach ($graph->getNode() as $node) {
    var_dump("Node: " . $node);

    foreach ($graph->getEdge($node) as $edge => $width) {
        [$node1, $node2, $size] = $width;
        if ($node1 instanceof GraphNode && $node2 instanceof GraphNode) {
            var_dump($node1->getName() . " - Connected with: " . $node2->getName());
        } else if ($node2 instanceof GraphNode) {
            var_dump($node1 . " - Connected with: " . $node2->getName());
        } else if ($node1 instanceof GraphNode) {
            var_dump($node1->getName() . " - Connected with: " . $node2);
        }
    }

    var_dump('---');
}


