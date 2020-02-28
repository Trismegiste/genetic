<?php

namespace Trismegiste\Genetic\Game\Vda;

use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\Fighter;
use Trismegiste\Genetic\Game\MutableFighterFactory;
use Trismegiste\Genetic\Game\PopulationLogger;

/**
 * Vampire Dark Age V20
 */
class FreeEvolution extends DarwinWorld {

    protected $factory;

    public function __construct(int $size, MutableFighterFactory $fac, PopulationLogger $log) {
        $this->factory = $fac;
        $this->logger = $log;
        $this->population = [];
        for ($k = 0; $k < $size; $k++) {
            $this->population[] = $fac->createRandom();
        }
    }

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

    protected function selectAndMutate($extinctRatio) {
        $extinctCount = $extinctRatio * $this->getSize();
        for ($idx = 0; $idx < $extinctCount; $idx++) {
            $partnerIdx = $this->getSize() - 1 - $idx;
            $child = $this->factory->createSpawn([$this->population[$idx], $this->population[$partnerIdx]]);
            $child->mutate();
            $this->population[$partnerIdx] = $child;
        }
    }

}
