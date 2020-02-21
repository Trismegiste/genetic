<?php

namespace Trismegiste\Genetic\Game\Vda;

use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\Fighter;
use Trismegiste\Genetic\Game\PopulationLogger;

/**
 * Vampire Dark Age V20
 */
class FreeEvolution extends DarwinWorld {

    public function __construct(int $size, CharacterFactory $fac, PopulationLogger $log) {
        $this->logger = $log;
        $this->population = [];
        for ($k = 0; $k < $size; $k++) {
            $this->population[] = $fac->createRandom();
        }
    }

    protected function startBattle(Fighter $pc1, Fighter $pc2) {
        
    }

    protected function getInitiativeTurn(Fighter $pc1, Fighter $pc2) {
        $init1 = $pc1->rollInitiative();
        $init2 = $pc2->rollInitiative();

        if ($init1 > $init2) {
            return [$pc1, $pc2];
        } else if ($init1 < $init2) {
            return [$pc2, $pc1];
        } else {
            return mt_rand(0, 1) ? [$pc1, $pc2] : [$pc2, $pc1];
        }
    }

    protected function battle(Fighter $pc1, Fighter $pc2) {
        $this->startBattle($pc1, $pc2);

        while (!$pc1->isDead() && !$pc2->isDead()) {
            $pc1->startTurn();
            $pc2->startTurn();
            $player = $this->getInitiativeTurn($pc1, $pc2);
            do {
                $player[0]->evolve($player[1]);
                $player[1]->evolve($player[0]);
            } while ($pc1->canMakeAction() || $pc2->canMakeAction());
        }

        return $pc1->isDead() ? $pc2 : $pc1;
    }

}
