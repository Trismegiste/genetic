<?php

namespace Trismegiste\Genetic\Game\SaWo;

class Factory implements \Trismegiste\Genetic\Game\PopulationFactory {

    protected $size;

    public function __construct($s) {
        $this->size = $s;
    }

    public function create(): array {
        $pop = [];
        for ($k = 0; $k < $this->size; $k++) {
            $pc = new Character([
                'strength' => 2 * mt_rand(2, 6),
                'vigor' => 2 * mt_rand(2, 6),
                'spirit' => 2 * mt_rand(2, 6),
                'fighting' => 2 * mt_rand(2, 6),
                'agility' => 2 * mt_rand(2, 6),
                'benny' => Property\BennyStrat::getRandomStrat(),
                'block' => mt_rand(0, 2),
                'trademark' => mt_rand(0, 2),
                'attack' => Property\AttackStrat::getRandomStrat()
            ]);
            $pop[] = $pc;
        }

        return $pop;
    }

}
