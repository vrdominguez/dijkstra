<?php

namespace Dijkstra;

use Queues\ReversePriorityQueue;
use SplPriorityQueue;

class Graph
{
    /**
     * Internal representation of a graph
     * @var array
     */
    protected array $graph;

    /**
     * Instances a new graph from two arrays
     *
     * @param array $names Names of the nodes
     * @param array $graph Array of arrays which represents the graph
     * @return Graph
     */
    static public function graphFromArray(array $names, array $graph): Graph
    {
        $graphObj = new self();

        foreach ($graph as $fromIndex => $destinations) {
            $fomName = $names[$fromIndex];
            foreach ($destinations as $toIndex => $cost) {
                // Avoid cost 0 (no route or same from and to)
                if ($cost == 0)
                    continue;

                $toName = $names[$toIndex];
                if (!isset($graphObj->graph[$fomName]))
                    $graphObj->graph[$fomName] = [];

                array_push($graphObj->graph[$fomName], new Edge($fomName, $toName, $cost));
            }
        }

        return $graphObj;
    }

    /**
     * Calculates the lower cost to all nodes from the indicated one and populates
     * an array with the previous node to each one.
     *
     * @param string $fromNode Initial node for paths
     * @return array
     */
    public function calculateCostsFrom(string $fromNode): array
    {
        $costs = [];
        $costs[$fromNode] = 0;

        $visited = [];
        $previous = [];

        $queue = new ReversePriorityQueue();
        $queue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
        $queue->insert($fromNode, $costs[$fromNode]);

        $graphEdges = $this->graph;

        while ($queue->valid()) {
            list($currentNode,) = $queue->extract();

            if (isset($visited[$currentNode]))
                continue;

            $visited[$currentNode] = true;

            foreach ($graphEdges[$currentNode] as $edge) {
                $altCost = $costs[$currentNode] + $edge->getCost();
                $destination = $edge->getTo();

                if (!isset($costs[$destination]) || ($altCost < $costs[$destination])) {
                    $previous[$destination] = $currentNode;
                    $costs[$destination] = $altCost;
                    $queue->insert($destination, $costs[$destination]);
                }
            }
        }

        return [$costs, $previous];
    }

    /**
     * Recovers the path to the destination node within a "previous nodes" array
     *
     * @param array $paths
     * @param string $to
     * @return array
     */
    public function calculatePathsTo(array $paths, string $to): array
    {
        $currentNode = $to;
        $path = [];

        if (isset($paths[$currentNode]))
            array_unshift($path, $currentNode);

        while (isset($paths[$currentNode])) {
            $previousNode = $paths[$currentNode];

            array_unshift($path, $previousNode);

            $currentNode = $previousNode;
        }

        return $path;
    }

    /**
     * Calculate the lower cost path form the first node to all the other nodes in graph or,
     * if $to is provided, to the selected one.
     *
     * @param string $from
     * @param string $to
     * @return array
     * @throws SameOriginAndDestinationException
     */
    public function getShortestPath(string $from, string $to = ""): array
    {
        if ( $from == $to) {
            throw new SameOriginAndDestinationException("To has the same value as from");
        }
        list($costs, $prev) = $this->calculateCostsFrom($from);
        $destinations = ($to == "") ? array_keys($costs) : [$to];

        $paths = [];
        foreach ($destinations as $currentTo) {
            // Do not obtain path if fom and to are the same node
            if ( $from == $currentTo )
                continue;

            $path = $this->calculatePathsTo($prev, $currentTo);
            $paths[$currentTo] = [$path, $costs[$currentTo]];
        }

        return $paths;
    }
}