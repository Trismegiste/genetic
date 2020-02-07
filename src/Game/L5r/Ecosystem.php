<?php

namespace Trismegiste\Genetic\Game\L5r;

/**
 * Generic Ecosystem 
 */
abstract class Ecosystem {

    protected $population = [];

    public function getSize() {
        return count($this->population);
    }

    /**
     * Battle between 2 PC
     * 
     * @param Character $pc1
     * @param Character $pc2
     * @return Character
     */
    protected function battle(Character $pc1, Character $pc2) {
        $player = [];

        $init1 = $pc1->rollInit();
        $init2 = $pc2->rollInit();

        if ($init1 === $init2) {
            if (rand(1, 2) === 1) {
                $init1++;
            }
        }

        if ($init1 >= $init2) {
            $player = [$pc1, $pc2];
        } else {
            $player = [$pc2, $pc1];
        }

        while (!$pc1->isDead() && !$pc2->isDead()) {
            if (!$player[0]->isDead()) {
                $player[1]->receiveAttack($player[0]);
            }
            if (!$player[1]->isDead()) {
                $player[0]->receiveAttack($player[1]);
            }
        }

        return $pc1->isDead() ? $pc2 : $pc1;
    }

}
