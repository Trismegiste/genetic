<?php

namespace Trismegiste\Genetic\Game\L5r;

use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\PopulationLogger;

/**
 * only text
 */
class TextLogger implements PopulationLogger {

    protected $console;

    public function __construct(OutputInterface $out) {
        $this->console = $out;
    }

    public function log(array &$pop) {
        foreach ([0, 1, 2, 5, 9] as $idx) {
            $this->console->writeln("$idx - " . $pop[$idx]);
        }
    }

    public function endLog() {
        
    }

}
