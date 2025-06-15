<?php

namespace Checkout\Common;

class TelemetryQueue
{
    const MAX_COUNT_IN_TELEMETRY_QUEUE = 10;
    private $queue;
    private $mutex;

    public function __construct()
    {
        $this->queue = [];
        $this->mutex = fopen(sys_get_temp_dir() . '/telemetry_queue.lock', 'w+');
    }

    public function __destruct()
    {
        if ($this->mutex) {
            fclose($this->mutex);
        }
    }

    public function enqueue($metrics)
    {
        flock($this->mutex, LOCK_EX);
        try {
            if (count($this->queue) < self::MAX_COUNT_IN_TELEMETRY_QUEUE) {
                $this->queue[] = $metrics;
            }
        } finally {
            flock($this->mutex, LOCK_UN);
        }
    }

    public function dequeue()
    {
        flock($this->mutex, LOCK_EX);
        try {
            if (empty($this->queue)) {
                return null;
            }
            return array_shift($this->queue);
        } finally {
            flock($this->mutex, LOCK_UN);
        }
    }
}
