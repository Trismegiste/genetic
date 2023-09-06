<?php

namespace Trismegiste\Genetic\Game\L5r;

use Trismegiste\Genetic\Game\L5r\Property\Stance;
use Trismegiste\Genetic\Game\L5r\Property\VoidStrategy;
use Trismegiste\Genetic\Game\MutableFighterFactory;
use Trismegiste\Genetic\Game\PopulationLogger;

/**
 * ComparedEcosystem is a competition with reference population
 */
class ComparedEcosystem extends Ecosystem
{

    protected $referencePop = [];

    public function __construct(int $size, MutableFighterFactory $fac, PopulationLogger $log, array $opponent, int $refSize)
    {
        parent::__construct($size, $fac, $log);

        // init population for reference
        for ($k = 0; $k < $refSize; $k++) {
            $opponent['voidStrat'] = VoidStrategy::getRandomStrat();
            $opponent['stance'] = Stance::getRandomStrat();
            $this->referencePop[] = $fac->create($opponent);
        }
    }

    /**
     * Completely override because new tournament algorithm
     */
    protected function tournament(int $round)
    {
        foreach ($this->referencePop as $pc1) {
            foreach ($this->population as $pc2) {
                $this->evaluateBestFighter($round, $pc1, $pc2);
            }
        }
    }

    /**
     * Overload for reinitialising referencePop
     */
    public function evolve(int $round, float $extinctRatio)
    {
        foreach ($this->referencePop as $pc) {
            $pc->newGeneration();
        }

        parent::evolve($round, $extinctRatio);
    }

}
