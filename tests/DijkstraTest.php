<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Dijkstra\Graph;
use Dijkstra\Edge;
use Dijkstra\SameOriginAndDestinationException;

class DijkstraTest extends TestCase
{
    /**
     * Test instancing objects
     */
    public function testInstances(): void
    {
        $edge = new Edge('Santiago', 'Ourense', 1);
        $this->assertInstanceOf(Edge::class, $edge, 'Instanced Edge');

        $graph = $this->instanceGraph();
        $this->assertInstanceOf(Graph::class, $graph, 'Instanced Graph');
    }

    /**
     * Test obtain route between two points
     */
    public function testCanObtainRoute(): void
    {
        $expectedResult = [
            'Ciudad Real' => [['Logroño', 'Zaragoza', 'Lleida', 'Castellón', 'Ciudad Real'], 16]
        ];

        $graph = $this->instanceGraph();
        try {
            $path = $graph->getShortestPath('Logroño', 'Ciudad Real');
        } catch (Exception $e) {
            $this->fail('Unexpected ' . $e::class . ' exception: ' . $e->getMessage());
        }

        $this->assertArrayHasKey('Ciudad Real', $path, 'Obtained route');
        $this->assertEquals($expectedResult, $path, 'Correct path and cost');

        // Check exception
        $this->expectException(SameOriginAndDestinationException::class);
        $graph->getShortestPath('Madrid', 'Madrid');
    }

    /**
     * Test obtain multiple routes
     */
    function testObtainAllRoutesFrom(): void
    {
        $expectedResults = [
            'Logroño' => [['Madrid', 'Logroño'], 8],
            'Teruel' => [['Madrid', 'Teruel'], 3],
            'Zaragoza' => [['Madrid', 'Teruel', 'Zaragoza'], 5],
            'Lleida' => [['Madrid', 'Teruel', 'Zaragoza', 'Lleida'], 7],
            'Alicante' => [['Madrid', 'Teruel', 'Alicante'], 10],
            'Castellón' => [['Madrid', 'Teruel', 'Zaragoza', 'Lleida', 'Castellón'], 11],
            'Segovia' => [['Madrid', 'Teruel', 'Zaragoza', 'Lleida', 'Segovia'], 15],
            'Ciudad Real' => [['Madrid', 'Teruel', 'Alicante', 'Ciudad Real'], 17],
        ];

        $graph = $this->instanceGraph();
        try {
            $paths = $graph->getShortestPath('Madrid');
        } catch (Exception $e) {
            $this->fail('Unexpected ' . $e::class . ' exception: ' . $e->getMessage());
        }

        $this->assertArrayNotHasKey("Madrid", $paths, 'No path from Madrid to Madrid');
        $this->assertEquals($expectedResults, $paths, 'All paths obtained are correct');
    }

    /**
     * Creates a new graph with data
     *
     * @return Graph
     */
    private function instanceGraph(): Graph
    {
        $cities = ['Logroño', 'Zaragoza', 'Teruel', 'Madrid', 'Lleida', 'Alicante', 'Castellón', 'Segovia', 'Ciudad Real'];
        $connections = [
            [0, 4, 6, 8, 0, 0, 0, 0, 0],
            [4, 0, 2, 0, 2, 0, 0, 0, 0],
            [6, 2, 0, 3, 5, 7, 0, 0, 0],
            [8, 0, 3, 0, 0, 0, 0, 0, 0],
            [0, 2, 5, 0, 0, 0, 4, 8, 0],
            [0, 0, 7, 0, 0, 0, 3, 0, 7],
            [0, 0, 0, 0, 4, 3, 0, 0, 6],
            [0, 0, 0, 0, 8, 0, 0, 0, 4],
            [0, 0, 0, 0, 0, 7, 6, 4, 0],
        ];

        return Graph::graphFromArray($cities, $connections);
    }
}