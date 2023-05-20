<?php

namespace GSpataro\CLI\Helper;

final class Stopwatch
{
    /**
     * Store stopwatch start time
     *
     * @var float
     */

    private float $startTime = 0;

    /**
     * Init a stopwatch and returns the start time
     *
     * @return float
     */

    public function start(): float
    {
        $this->startTime = microtime(true);

        return $this->startTime;
    }

    /**
     * Get a step of the stopwatch
     *
     * @return float
     */

    public function step(): float
    {
        return $this->startTime !== 0.0 ? microtime(true) - $this->startTime : 0.0;
    }

    /**
     * Stop the stopwatch
     *
     * @return float
     */

    public function stop(): float
    {
        $lastReading = $this->step();
        $this->startTime = 0.0;

        return $lastReading;
    }
}
