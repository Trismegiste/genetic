<?php

namespace Trismegiste\Genetic\Game\L5r;

use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\Fighter;
use Trismegiste\Genetic\Game\MutableFighterFactory;
use Trismegiste\Genetic\Game\PopulationLogger;

/**
 * Generic Ecosystem for L5R
 */
class Ecosystem extends DarwinWorld {

    public function __construct(int $size, MutableFighterFactory $fac, PopulationLogger $log) {
        $this->factory = $fac;
        $this->logger = $log;
        $this->population = [];
        for ($k = 0; $k < $size; $k++) {
            $this->population[] = $fac->createRandom();
        }
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

}
