<?php

namespace Trismegiste\Genetic\Game\L5r;

use Trismegiste\Genetic\Game\DarwinWorld;

/**
 * Generic Ecosystem 
 */
abstract class Ecosystem extends DarwinWorld {

    /**
     * Factory
     * 
     * @param array $popSize an array of Character
     */
    protected function createRandomPopulation($popSize) {
        $population = [];
        for ($k = 0; $k < $popSize; $k++) {
            $pc = $this->createPc("L5R", [
                'voidStrat' => Property\VoidStrategy::getRandomStrat(),
                'stance' => Property\Stance::getRandomStrat(),
                'agility' => rand(2, 6),
                'reflexe' => rand(2, 6),
                'earth' => rand(2, 6),
                'kenjutsu' => rand(1, 5),
                'void' => rand(2, 5),
                'strength' => rand(2, 6)
            ]);
            $population[] = $pc;
        }

        return $population;
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

    /**
     * Factory
     * 
     * @param type $name
     * @param type $param
     * @return Character
     */
    protected function createPc($name, $param = []) {
        return new Character($name, $param);
    }

    protected function getReport() {
        $report = [];
        foreach ([0, 1, 2, 5, 9] as $idx) {
            $report[] = "$idx - " . $this->population[$idx];
        }

        return $report;
    }

}
