<?php

namespace Trismegiste\Genetic\Game;

/**
 * Aggregate of multiple loggers
 */
class AggregateLogger implements PopulationLogger {

    protected $logger;

    public function __construct(array $log = []) {
        foreach ($log as $idx => $item) {
            if (!($item instanceof PopulationLogger)) {
                throw new \InvalidArgumentException("Logger $idx is not a PopulationLogger");
            }
        }
        $this->logger = $log;
    }

    public function endLog() {
        foreach ($this->logger as $log) {
            $log->endLog();
        }
    }

    public function log(array &$pop) {
        foreach ($this->logger as $log) {
            $log->log($pop);
        }
    }

    public function push(PopulationLogger $log) {
        $this->logger[] = $log;
    }

}
