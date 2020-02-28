<?php

namespace Trismegiste\Genetic\Game\Vda;

use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\Fighter;

/**
 * Vampire Dark Age V20
 */
class FreeEvolution extends DarwinWorld {

    protected function getInitiativeTurn(Fighter $pc1, Fighter $pc2) {
        $init1 = $pc1->rollInitiative();
        $init2 = $pc2->rollInitiative();

        if ($init1 === $init2) {
            $init1 += 2 * mt_rand(0, 1) - 1;
        }

        return ($init1 > $init2) ? [$pc1, $pc2] : [$pc2, $pc1];
    }

    protected function battle(Fighter $pc1, Fighter $pc2) {
        while (!$pc1->isDead() && !$pc2->isDead()) {
            $pc1->startTurn();
            $pc2->startTurn();
            $player = $this->getInitiativeTurn($pc1, $pc2);
            do {
                $player[0]->evolve($player[1]);
                $player[1]->evolve($player[0]);
            } while ($pc1->canMakeAttack() || $pc2->canMakeAttack());
        }

        return $pc1->isDead() ? $pc2 : $pc1;
    }

    protected function selectPopulation(float $extinctRatio) {
        $this->crossingAndMutateStrategy($extinctRatio);
    }

}
