<?php

namespace Trismegiste\Genetic\Game;

use Trismegiste\Genetic\Game\Mutable;

/**
 * An abstract class for genetic algorithm
 * Works with Mutable object
 */
abstract class DarwinWorld {

    /** @var Mutable */
    protected $population = [];

    public function getSize() {
        return count($this->population);
    }

    /**
     * Tournament between population to evalute a Fitness
     */
    abstract protected function tournament($round);

    /**
     * Runs one generation
     * 
     * @param int $round how many rounds to determine the winner between 2 fighter
     * @param float $extinctRatio A ratio between [0,1] of how many PC gets extinct by the natural selection. Ex: 0.05 means 5% of PC will be replaced by the best fitted with mutation
     * @return array an array of string containing a selection of PC for printing
     */
    public function evolve($round, $extinctRatio) {
        // re-initialise pop
        foreach ($this->population as $pc) {
            $pc->newGeneration();
        }

        $this->tournament($round);

        usort($this->population, function($a, $b) {
            return $b->getFitness() - $a->getFitness();
        });

        $this->selectAndMutate($extinctRatio);

        $report = $this->getReport();

        return $report;
    }

    protected function selectAndMutate($extinctRatio) {
        $extinctIdx = (1 - $extinctRatio) * $this->getSize();
        // select & mutate
        foreach ($this->population as $idx => $pc) {
            if ($idx >= $extinctIdx) {
                // we clone & mutate the best fit to replace the worst fit
                $npc = clone $this->population[$idx - $extinctIdx];
                $npc->mutate();
                $this->population[$idx] = $npc;
            }
        }
    }

    abstract protected function getReport();
}
