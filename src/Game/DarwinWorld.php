<?php

namespace Trismegiste\Genetic\Game;

/**
 * An abstract class for genetic algorithm
 * Works with Mutable object
 */
abstract class DarwinWorld {

    /** @var Mutable */
    protected $population = [];
    protected $logger;

    /**
     * Ctor
     */
    public function __construct(PopulationFactory $fac, PopulationLogger $log) {
        $this->population = $fac->create();
        $this->logger = $log;
    }

    /**
     * Tournament between population to evalute a Fitness
     */
    protected function tournament(int $round) {
        foreach ($this->population as $idx1 => $pc1) {
            foreach ($this->population as $idx2 => $pc2) {
                if ($idx2 <= $idx1) {
                    continue;
                }
                $this->evaluateBestFighter($round, $pc1, $pc2);
            }
        }
    }

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
    public function evolve(int $round, $extinctRatio) {
        // re-initialise pop
        foreach ($this->population as $pc) {
            $pc->newGeneration();
        }

        $this->tournament($round);

        usort($this->population, function($a, $b) {
            return $b->getFitness() - $a->getFitness();
        });

        $this->selectAndMutate($extinctRatio);

        $this->logger->log($this->population);
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

    protected function evaluateBestFighter($round, Mutable $pc1, Mutable $pc2) {
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
        // many cases are missed : equality for example. We don't care, we want a threshold effect
    }

    /**
     * Who strikes first ?
     * @return array [$pc1, $pc2] or [$pc2, $pc1]
     */
    abstract protected function getInitiativeTurn(Fighter $pc1, Fighter $pc2);

    /**
     * Battle between 2 PC
     * 
     * @param Fighter $pc1
     * @param Fighter $pc2
     * @return Fighter the winner
     */
    protected function battle(Fighter $pc1, Fighter $pc2) {
        $player = $this->getInitiativeTurn($pc1, $pc2);

        while (!$pc1->isDead() && !$pc2->isDead()) {
            if (!$player[0]->isDead()) {
                $player[1]->receiveAttack($player[0]);
            }
            if (!$player[1]->isDead()) {
                $player[0]->receiveAttack($player[1]);
            }
        }

        return $pc1->isDead() ? $pc2 : $pc1;
    }

}
