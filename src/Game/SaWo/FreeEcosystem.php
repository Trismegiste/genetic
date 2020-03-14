<?php

namespace Trismegiste\Genetic\Game\SaWo;

use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\Fighter;

/**
 * A Free evolution for SaWo
 */
class FreeEcosystem extends DarwinWorld {

    protected function getInitiativeTurn(Fighter $pc1, Fighter $pc2): array {
        $player = [];
        if (mt_rand(0, 1)) {
            $player = [$pc1, $pc2];
        } else {
            $player = [$pc2, $pc1];
        }

        return $player;
    }

    protected function selectPopulation(float $extinctRatio) {
        $this->crossingAndMutateStrategy($extinctRatio);
    }

}
