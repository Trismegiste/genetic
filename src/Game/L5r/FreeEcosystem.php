<?php

namespace Trismegiste\Genetic\Game\L5r;

/**
 * FreeEcosystem is a free competition between PC
 */
class FreeEcosystem extends Ecosystem {

    public function __construct($popSize) {
        $this->population = $this->createRandomPopulation($popSize);
    }

    protected function tournament($round) {
        foreach ($this->population as $idx1 => $pc1) {
            foreach ($this->population as $idx2 => $pc2) {
                if ($idx2 <= $idx1) {
                    continue;
                }
                $this->evaluateBestFighter($round, $pc1, $pc2);
            }
        }
    }

}
