<?php

namespace Trismegiste\Genetic\Game\SaWo;

use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\Fighter;
use Trismegiste\Genetic\Game\MutableFighterFactory;
use Trismegiste\Genetic\Game\PopulationLogger;

/**
 * A Free evolution for SaWo
 */
class FreeEcosystem extends DarwinWorld {

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
        $player = [];
        if (mt_rand(0, 1)) {
            $player = [$pc1, $pc2];
        } else {
            $player = [$pc2, $pc1];
        }

        return $player;
    }

}
