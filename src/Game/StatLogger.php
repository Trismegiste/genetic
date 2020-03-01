<?php

namespace Trismegiste\Genetic\Game;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Print stats analysis
 */
class StatLogger implements PopulationLogger {

    protected $console;

    public function __construct(OutputInterface $out) {
        $this->console = $out;
    }

    public function endLog() {
        
    }

    public function log(array &$pop) {
        // print average cost
        $sum = array_reduce($pop, function ($carry, $item) {
            $carry += $item->getCost();
            return $carry;
        }, 0);
        $this->console->writeln("Average Cost = " . $sum / count($pop));
    }

}
