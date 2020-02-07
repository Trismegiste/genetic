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

    /**
     * Ctor
     * @param int $popSize population size
     */
    public function __construct($popSize) {
        $this->population = $this->createPopulation($popSize);
    }

    /**
     * Factory : creates a population
     * @return array an array of Mutable
     */
    abstract protected function createPopulation($popSize);

    /**
     * Tournament between population to evalute a Fitness
     */
    abstract protected function tournament($round);

    /**
     * Returns a report
     * @return array an array of string
     */
    abstract protected function getReport();

    /**
     * Getter for population size
     * @return int
     */
    public function getSize() {
        return count($this->population);
    }

    /**
     * Runs one generation
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

    /**
     * Kills the worst fitted PC and replaces them with best fitted PC with mutation
     * @param float $extinctRatio A ratio between [0,1]
     * @see class::evolve()
     */
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

}
