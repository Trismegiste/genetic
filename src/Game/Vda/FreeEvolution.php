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

    protected function getInitiativeTurn(Fighter $pc1, Fighter $pc2): array {
        
    }

}
