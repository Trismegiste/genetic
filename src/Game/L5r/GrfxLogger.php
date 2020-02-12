<?php

namespace Trismegiste\Genetic\Game\L5r;

use Trismegiste\Genetic\Util\PlotterXY;

/**
 * Logger with plotting
 */
class GrfxLogger extends TextLogger {

    protected $plotData;

    public function log(array &$pop) {
        parent::log($pop);

        $this->plotData[] = array_map(function($pc) {
            return ['x' => $pc->getCost(), 'y' => $pc->getVictory()];
        }, $pop);
    }

    public function writeGraphic(string $filename) {
        $im = new PlotterXY(1920, 1080);
        $im->draw($this->plotData);
        $im->writePng($filename);
    }

}
