<?php

namespace Trismegiste\Genetic\Game\L5r;

use Trismegiste\Genetic\Game\L5r\Property\Stance;
use Trismegiste\Genetic\Game\L5r\Property\VoidStrategy;
use Trismegiste\Genetic\Game\PopulationFactory;

/**
 * ComparedEcosystem is a competition with reference population
 */
class ComparedEcosystem extends Ecosystem {

    protected $referencePop = [];

    public function __construct(PopulationFactory $fac, $opponent, $refSize) {
        parent::__construct($fac);

        // init population for reference
        for ($k = 0; $k < $refSize; $k++) {
            $opponent['voidStrat'] = VoidStrategy::getRandomStrat();
            $opponent['stance'] = Stance::getRandomStrat();
            $pc = new Character('L5R', $opponent);
            $this->referencePop[] = $pc;
        }
    }

    protected function tournament(int $round) {
        foreach ($this->referencePop as $pc1) {
            foreach ($this->population as $pc2) {
                $this->evaluateBestFighter($round, $pc1, $pc2);
            }
        }
    }

    public function evolve(int $round, $extinctRatio) {
        foreach ($this->referencePop as $pc) {
            $pc->newGeneration();
        }

        return parent::evolve($round, $extinctRatio);
    }

    protected function getReport() {
        $report = parent::getReport();

        usort($this->referencePop, function($a, $b) {
            return $b->getVictory() - $a->getVictory();
        });

        array_unshift($report, "Ref: " . $this->referencePop[0]);

        return $report;
    }

}
