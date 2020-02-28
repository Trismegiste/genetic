<?php

namespace Trismegiste\Genetic\Game;

use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\PopulationLogger;

/**
 * Logger
 */
class TextLogger implements PopulationLogger {

    protected $console;
    protected $viewedRatio;

    /**
     * Ctor
     * @param OutputInterface $out
     * @param float $viewed a float between 0 to 1 of how many item are printed for checking
     */
    public function __construct(OutputInterface $out, float $viewed) {
        $this->console = $out;
        $this->viewedRatio = $viewed;
    }

    public function log(array &$pop) {
        for ($idx = 0; $idx < ($this->viewedRatio * count($pop)); $idx++) {
            $this->console->writeln("$idx - " . $pop[$idx]);
        }
        // print average cost
        $sum = array_reduce($pop, function ($carry, $item) {
            $carry += $item->getCost();
            return $carry;
        }, 0);
        $this->console->writeln("Average Cost = " . $sum / count($pop));
    }

    public function endLog() {
        
    }

}
