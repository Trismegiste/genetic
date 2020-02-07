<?php

namespace Trismegiste\Genetic\Game\L5r;

/**
 * ComparedEcosystem is a competition with reference population
 */
class ComparedEcosystem extends Ecosystem {

    protected $referencePop = [];

    public function __construct($popSize, $opponent, $refSize) {
        $this->population = $this->createRandomPopulation($popSize);

        // init population for reference
        for ($k = 0; $k < $refSize; $k++) {
            $opponent['voidStrat'] = Property\VoidStrategy::getRandomStrat();
            $opponent['stance'] = Property\Stance::getRandomStrat();
            $pc = new Character('L5R', $opponent);
            $this->referencePop[] = $pc;
        }
    }

    protected function tournament($round) {
        foreach ($this->referencePop as $pc1) {
            foreach ($this->population as $pc2) {
                $delta = $pc1->getCost() - $pc2->getCost();

                $key1 = spl_object_hash($pc1);
                $key2 = spl_object_hash($pc2);
                $win = [$key1 => 0, $key2 => 0];
                for ($k = 0; $k < $round; $k++) {
                    $pc1->restart();
                    $pc2->restart();
                    $winner = $this->battle($pc1, $pc2);
                    $win[spl_object_hash($winner)] ++;
                }

                if (($win[$key1] > $win[$key2]) && ($delta <= 0)) {
                    $pc1->incVictory();
                }
                if (($win[$key1] < $win[$key2]) && ($delta >= 0)) {
                    $pc2->incVictory();
                }
                // many cases are missed : equality. We don't care, we want a threshold effect
            }
        }
    }

    public function getFirstReference() {
        return $this->referencePop[0];
    }

}
