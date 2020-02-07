<?php

namespace Trismegiste\Genetic\Game\L5r;

/**
 * ComparedEcosystem is a competition with reference population
 */
class ComparedEcosystem extends Ecosystem {

    protected $referencePop = [];

    public function __construct($popSize, $opponent, $refSize) {
        parent::__construct($popSize);

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
                $this->evaluateBestFighter($round, $pc1, $pc2);
            }
        }
    }

    public function evolve($round, $extinctRatio) {
        foreach ($this->referencePop as $pc) {
            $pc->newGeneration();
        }

        return parent::evolve($round, $extinctRatio);
    }

    protected function getReport() {
        $report = parent::getReport();

        usort($this->referencePop, function($a, $b) {
            return $b->getWinningCount() - $a->getWinningCount();
        });

        array_unshift($report, "Ref: " . $this->referencePop[0]);

        return $report;
    }

}
