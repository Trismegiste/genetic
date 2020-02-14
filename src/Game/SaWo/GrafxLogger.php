<?php

namespace Trismegiste\Genetic\Game\SaWo;

use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\SaWo\TextLogger;
use Trismegiste\Genetic\Util\ImagePlotter;

/**
 * Logger with iage
 */
class GrafxLogger extends TextLogger {

    protected $plotData;
    protected $plotter;

    public function __construct(OutputInterface $out, ImagePlotter $plot) {
        parent::__construct($out);
        $this->plotter = $plot;
    }

    public function log(array &$pop) {
        parent::log($pop);

        $this->plotData[] = array_map(function($pc) {
            return ['x' => $pc->getCost(), 'y' => $pc->getVictory()];
        }, $pop);
    }

    public function endLog() {
        $this->plotter->draw($this->plotData);
    }

}
