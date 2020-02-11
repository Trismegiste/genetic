<?php

namespace Trismegiste\Genetic\Game\SaWo;

use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\Fighter;

/**
 * A Free evolution for SaWo
 */
class FreeEcosystem extends DarwinWorld {

    public function getReport() {
        $report = [];
        foreach ([0, 1, 2, 5, 9] as $idx) {
            $report[] = "$idx - " . $this->population[$idx];
        }

        return [
            'grafx' => array_map(function($pc) {
                        return ['x' => $pc->getCost(), 'y' => $pc->getVictory()];
                    }, $this->population),
            'text' => $report
        ];
    }

    protected function getInitiativeTurn(Fighter $pc1, Fighter $pc2) {
        $player = [];
        if (mt_rand(0, 1)) {
            $player = [$pc1, $pc2];
        } else {
            $player = [$pc2, $pc1];
        }

        return $player;
    }

}
