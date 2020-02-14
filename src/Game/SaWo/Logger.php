<?php

namespace Trismegiste\Genetic\Game\SaWo;

use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\PopulationLogger;
use Trismegiste\Genetic\Util\ImagePlotter;

/**
 * Logger
 */
class Logger implements PopulationLogger {

    protected $plotData;
    protected $console;
    protected $plotter;

    public function __construct(OutputInterface $out, ImagePlotter $im) {
        $this->console = $out;
        $this->plotter = $im;
    }

    public function log(array& $pop) {
        foreach ([0, 1, 2, 5, 9] as $idx) {
            $this->console->writeln("$idx - " . $pop[$idx]);
        }

        $this->plotData[] = array_map(function($pc) {
            return ['x' => $pc->getCost(), 'y' => $pc->getVictory()];
        }, $pop);
    }

    public function endLog() {
        $this->plotter->draw($this->plotData);
    }

}
