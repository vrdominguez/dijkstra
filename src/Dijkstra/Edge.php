<?php

namespace Dijkstra;

/**
 * Edge implements the path between two nodes of a graph.
 *
 * Edge is the logical representation of a direct path between two nodes in a
 * graph, including the cost of the path.
 *
 * @author  Víctor R. Rodríguez Domínguez <victor@vrdominguez.es>
 * @version $Revision: 1.0 $
 *
 */
class Edge
{
    /**
     * @param string $from Origin node name
     * @param string $to Destination node name
     * @param int $cost Cost of the edge
     */
    public function __construct(
        private string $from,
        private string $to,
        private int    $cost)
    {
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }


}