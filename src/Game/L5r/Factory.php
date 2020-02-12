<?php

namespace Trismegiste\Genetic\Game\L5r;

/**
 * Factory for population
 */
class Factory implements \Trismegiste\Genetic\Game\PopulationFactory {

    protected $size;

    public function __construct($s) {
        $this->size = $s;
    }

    public function create(): array {
        $population = [];
        for ($k = 0; $k < $this->size; $k++) {
            $pc = new Character("L5R", [
                'voidStrat' => Property\VoidStrategy::getRandomStrat(),
                'stance' => Property\Stance::getRandomStrat(),
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

}
