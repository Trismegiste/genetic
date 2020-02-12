<?php

namespace Trismegiste\Genetic\Game\L5r;

use Trismegiste\Genetic\Game\L5r\Property\Stance;
use Trismegiste\Genetic\Game\L5r\Property\VoidStrategy;
use Trismegiste\Genetic\Game\PopulationFactory;
use Trismegiste\Genetic\Game\PopulationLogger;

/**
 * ComparedEcosystem is a competition with reference population
 */
class ComparedEcosystem extends Ecosystem {

    protected $referencePop = [];

    public function __construct(PopulationFactory $fac, PopulationLogger $log, $opponent, $refSize) {
        parent::__construct($fac, $log);

        // init population for reference
        for ($k = 0; $k < $refSize; $k++) {
            $opponent['voidStrat'] = VoidStrategy::getRandomStrat();
            $opponent['stance'] = Stance::getRandomStrat();
            $pc = new Character('L5R', $opponent);
            $this->referencePop[] = $pc;
        }
    }

    /**
     * Completely override because new tournament algorithm
     */
    protected function tournament(int $round) {
        foreach ($this->referencePop as $pc1) {
            foreach ($this->population as $pc2) {
                $this->evaluateBestFighter($round, $pc1, $pc2);
            }
        }
    }

    /**
     * Overload for reinitialising referencePop
     */
    public function evolve(int $round, $extinctRatio) {
        foreach ($this->referencePop as $pc) {
            $pc->newGeneration();
        }

        parent::evolve($round, $extinctRatio);
    }

}
