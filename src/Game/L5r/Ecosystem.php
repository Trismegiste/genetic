<?php

namespace Trismegiste\Genetic\Game\L5r;

use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\Fighter;
use Trismegiste\Genetic\Game\L5r\Property\Stance;
use Trismegiste\Genetic\Game\L5r\Property\VoidStrategy;

/**
 * Generic Ecosystem for L5R
 */
abstract class Ecosystem extends DarwinWorld {

    protected function createPopulation(int $popSize) {
        $population = [];
        for ($k = 0; $k < $popSize; $k++) {
            $pc = $this->createPc("L5R", [
                'voidStrat' => VoidStrategy::getRandomStrat(),
                'stance' => Stance::getRandomStrat(),
                'agility' => mt_rand(2, 6),
                'reflexe' => mt_rand(2, 6),
                'earth' => mt_rand(2, 6),
                'kenjutsu' => mt_rand(1, 5),
                'void' => mt_rand(2, 5),
                'strength' => mt_rand(2, 6)
            ]);
            $population[] = $pc;
        }

        return $population;
    }

    protected function getInitiativeTurn(Fighter $pc1, Fighter $pc2) {
        $player = [];

        $init1 = $pc1->rollInit();
        $init2 = $pc2->rollInit();

        if ($init1 === $init2) {
            if (mt_rand(1, 2) === 1) {
                $init1++;
            }
        }

        if ($init1 >= $init2) {
            $player = [$pc1, $pc2];
        } else {
            $player = [$pc2, $pc1];
        }

        return $player;
    }

    /**
     * Factory
     * 
     * @param string $name
     * @param array $param
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
