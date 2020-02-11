<?php

namespace Trismegiste\Genetic\Game\L5r;

/**
 * FreeEcosystem is a free competition between a random population of L5R PC
 */
class FreeEcosystem extends Ecosystem {

    public function getReport() {
        return [
            'grafx' => array_map(function($pc) {
                        return ['x' => $pc->getCost(), 'y' => $pc->getVictory()];
                    }, $this->population),
            'text' => parent::getReport()
        ];
    }

}
