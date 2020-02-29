<?php

namespace Trismegiste\Genetic\Game;

use Trismegiste\Genetic\Util\ImagePlotter;

/**
 * Logger with image
 */
class GrafxLogger implements PopulationLogger {

    protected $plotData;
    protected $plotter;

    public function __construct(ImagePlotter $plot) {
        $this->plotter = $plot;
    }

    public function log(array &$pop) {
        $this->plotData[] = array_map(function($pc) {
            return ['x' => $pc->getCost(), 'y' => $pc->getFitness()];
        }, $pop);
    }

    public function endLog() {
        $this->plotter->draw($this->plotData);
    }

}
