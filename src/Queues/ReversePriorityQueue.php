<?php

namespace Queues;

use SplPriorityQueue;

/**
 * ReversePriorityQueue a Priority Queue with reverse order.
 *
 * ReversePriorityQueue implements a priority queue with order reversed form
 * what SplPriorityQueue does.
 *
 *
 * @author  Víctor R. Rodríguez Domínguez <victor@vrdominguez.es>
 * @version $Revision: 1.0 $
 * @see     https://www.php.net/manual/en/class.splpriorityqueue.php
 *
 */
class ReversePriorityQueue extends SplPriorityQueue
{
    public const MAX_PRIORITY = 0x7FFFFFFF;

    /**
     * Insert value into the queue
     *
     * @param mixed $value Value to store
     * @param mixed $priority Weight/priority
     * @return bool
     * @access public
     *
     */
    public function insert($value, $priority): bool
    {
        // Recalculate priority
        $priority = self::MAX_PRIORITY - $priority;

        return parent::insert($value, $priority);
    }

    /**
     * Recovers current node from queue and recalculates priority (if necessary)
     *
     * @return mixed
     * @access public
     *
     */
    public function current(): mixed
    {
        if ($this->getExtractFlags() == SplPriorityQueue::EXTR_DATA)
            return parent::current();

        return $this->restorePriority(parent::current());
    }

    /**
     * Extracts current node from queue
     *
     * @return mixed
     * @access public
     *
     */
    public function extract(): mixed
    {
        if ($this->getExtractFlags() == SplPriorityQueue::EXTR_DATA)
            return parent::extract();

        return $this->restorePriority(parent::extract());
    }

    /**
     * Gets top node from queue
     *
     * @return mixed
     * @access public
     *
     */
    public function top(): mixed
    {
        if ($this->getExtractFlags() == SplPriorityQueue::EXTR_DATA)
            return parent::top();

        return $this->restorePriority(parent::top());
    }

    /**
     * Restores the priority value in node recover operations
     *
     * @param int|array $data
     * @return int|array
     */
    private function restorePriority(int|array $data): int|array
    {
        if (is_array($data)) {
            if (array_key_exists("priority", $data)) {
                $data["priority"] = self::MAX_PRIORITY - $data["priority"];
                return [$data["data"], $data["priority"]];
            }

            $data[1] = self::MAX_PRIORITY - $data[1];
            return $data;
        }

        return self::MAX_PRIORITY - $data;
    }
}

