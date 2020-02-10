<?php

namespace Trismegiste\Genetic\Game\SaWo;

use Trismegiste\Genetic\Game\DarwinWorld;

/**
 * A Free evolution for SaWo
 */
class FreeEcosystem extends DarwinWorld {

    protected function createPopulation($popSize): array {
        $pop = [];
        for ($k = 0; $k < $popSize; $k++) {
            $pc = new Character();
            $pop[] = $pc;
        }

        return $pop;
    }

    public function getReport() {
        return [
            'grafx' => array_map(function($pc) {
                        return ['x' => $pc->getCost(), 'y' => $pc->getWinningCount()];
                    }, $this->population),
            'text' => parent::getReport()
        ];
    }

    protected function tournament($round) {
        foreach ($this->population as $idx1 => $pc1) {
            foreach ($this->population as $idx2 => $pc2) {
                if ($idx2 <= $idx1) {
                    continue;
                }
                $this->evaluateBestFighter($round, $pc1, $pc2);
            }
        }
    }

}
