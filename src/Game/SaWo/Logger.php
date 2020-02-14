<?php

namespace Trismegiste\Genetic\Game\SaWo;

use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\PopulationLogger;
use Trismegiste\Genetic\Util\PlotterXY;

/**
 * Logger
 */
class Logger implements PopulationLogger {

    protected $plotData;
    protected $console;

    public function __construct(OutputInterface $out) {
        $this->console = $out;
    }

    public function log(array& $pop) {
        foreach ([0, 1, 2, 5, 9] as $idx) {
            $this->console->writeln("$idx - " . $pop[$idx]);
        }

        $this->plotData[] = array_map(function($pc) {
            return ['x' => $pc->getCost(), 'y' => $pc->getVictory()];
        }, $pop);
    }

    public function writeGraphic(string $filename) {
        $im = new PlotterXY(1920, 1080);
        $im->draw($this->plotData);
        $im->writePng($filename);
    }

    public function endLog() {
        // nothing to do
    }

}
