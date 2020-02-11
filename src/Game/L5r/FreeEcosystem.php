<?php

namespace Trismegiste\Genetic\Game\L5r;

/**
 * FreeEcosystem is a free competition between a random population of L5R PC
 */
class FreeEcosystem extends Ecosystem {

    protected function tournament(int $round) {
        foreach ($this->population as $idx1 => $pc1) {
            foreach ($this->population as $idx2 => $pc2) {
                if ($idx2 <= $idx1) {
                    continue;
                }
                $this->evaluateBestFighter($round, $pc1, $pc2);
            }
        }
    }

    public function getReport() {
        return [
            'grafx' => array_map(function($pc) {
                        return ['x' => $pc->getCost(), 'y' => $pc->getWinningCount()];
                    }, $this->population),
            'text' => parent::getReport()
        ];
    }

}
