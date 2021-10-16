<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Queues\ReversePriorityQueue;

final class QueueTest extends TestCase
{
    /**
     * Test values for queue
     *
     * @var array
     */
    protected array $testValues = [
        ["A",  4],
        ["B",  1],
        ["C",  9],
        ["D",  5],
        ["E",  0],
        ["F", 12],
    ];

    /**
     * Check that ReversePriorityQueue can be instanced
     */
    public function testCanInstanceQueue(): void {
        $queue = new ReversePriorityQueue();

        $this->assertInstanceOf( ReversePriorityQueue::class, $queue );
        $this->assertIsIterable($queue, "The queue is iterable");
    }

    /**
     * Check values can be inserted in queue
     */
    public function testInsertIntoQueue(): void {
        $queue = new ReversePriorityQueue();

        $counter = 0;
        foreach ( $this->testValues as $testValue ) {
            $queue->insert($testValue[0], $testValue[1]);
            $this->assertEquals(
                ++$counter,
                $queue->count(),
                'After insertion, there is ' . $counter . ' elements in queue'
            );
        }
    }

    /**
     * Check extract and top method (top used to check if extract deletes the first queue member).
     */
    public function testQueueExtractAndTop(): void {
        $queue = $this->populateQueueWithExampleValues();

        list( $firstElement, $previousPriority ) = $queue->top();
        $previousValue = "";
        $queueElements = $queue->count();

        // Extract elements from queue and check they are ordered by priority
        while ($queue->valid() ) {
            list($value, $priority) = $queue->extract();

            $this->assertNotEquals( $previousValue, $value, "Obtained new value from queue" );

            $this->assertGreaterThanOrEqual( $previousPriority, $priority, "New priority is >=" );

            $this->assertEquals( --$queueElements, $queue->count(), "Queue has 1 less element" );

            if ( $queueElements > 0) {
                $newTop = $queue->top();
                $this->assertNotEquals($firstElement, $newTop, "Removed first element from queue");
                $firstElement = $newTop;
            }
        }
    }

    /**
     * Check current and next methods
     */
    public function testQueueIterationAndCurrent() {
        $queue = $this->populateQueueWithExampleValues();

        $previousValue = "";
        $previousPriority = 0;

        while ( $queue->valid() ) {
            list($currentValue, $currentPriority) = $queue->current();

            $this->assertNotEquals($previousValue, $currentValue, "Obtained new element");
            $this->assertGreaterThanOrEqual( $previousPriority, $currentPriority, "New priority is >=" );

            // Step into next element
            $queue->next();
        }
    }

    /**
     * Check different extract flags values
     */
    private function checkExtractFlags(): void {
        $queue = $this->populateQueueWithExampleValues();
        $currentExtractFlags = $queue->getExtractFlags();

        $this->assertEquals(SplPriorityQueue::EXTR_BOTH, $currentExtractFlags, "Flag BOTH" );
        $this->assertIsArray($queue->current(), "Array with data and priority");

        $queue->setExtractFlags(SplPriorityQueue::EXTR_DATA);
        $this->assertEquals(SplPriorityQueue::EXTR_DATA, $currentExtractFlags, "Flag DATA" );
        $this->assertIsString($queue->current(), "Obtained data from queue");

        $queue->setExtractFlags(SplPriorityQueue::EXTR_PRIORITY);
        $this->assertEquals(SplPriorityQueue::EXTR_PRIORITY, $currentExtractFlags, "Flag PRIORITY" );
        $this->assertIsInt($queue->current(), "Obtained priority from queue");
    }

    /**
     * Creates a new queue with example values
     *
     * @return ReversePriorityQueue
     */
    private function populateQueueWithExampleValues(): ReversePriorityQueue {
        $queue = new ReversePriorityQueue();

        foreach ( $this->testValues as $testValue ) {
            $queue->insert($testValue[0], $testValue[1]);
        }

        $queue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
        return $queue;
    }
}