<?php

namespace Trismegiste\Genetic\Game;

/**
 * An abstract class for genetic algorithm
 * Works with Mutable object
 */
abstract class DarwinWorld
{

    /** @var Mutable */
    protected $population = [];
    protected $logger;
    protected $factory;

    /**
     * Ctor
     */
    public function __construct(int $size, MutableFighterFactory $fac, PopulationLogger $log)
    {
        $this->factory = $fac;
        $this->logger = $log;
        $this->population = [];
        for ($k = 0; $k < $size; $k++) {
            $this->population[] = $fac->createRandom();
        }
    }

    /**
     * Tournament between population to evalute a Fitness
     */
    protected function tournament(int $round)
    {
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
    public function getSize(): int
    {
        return count($this->population);
    }

    /**
     * Runs one generation
     * @param int $round how many rounds to determine the winner between 2 fighter
     * @param float $extinctRatio A ratio between [0,1] of how many PC gets extinct by the natural selection. Ex: 0.05 means 5% of PC will be replaced by the best fitted with mutation
     * @return array an array of string containing a selection of PC for printing
     */
    public function evolve(int $round, float $extinctRatio)
    {
        // re-initialise pop
        foreach ($this->population as $pc) {
            $pc->newGeneration();
        }

        $this->tournament($round);

        usort($this->population, function ($a, $b) {
            return $b->getFitness() - $a->getFitness();
        });

        $this->selectPopulation($extinctRatio);

        $this->logger->log($this->population);
    }

    /**
     * Selection processus for the population
     * Create your own selection process or use one the two method below
     * @see crossingAndMutateStrategy()
     * @see cloneAndMutateStrategy()
     */
    abstract protected function selectPopulation(float $extinctRatio);

    /**
     * Kills the worst fitted PC and replaces them with mutated children from the worst and best PC
     * @param float $extinctRatio
     * @see selectPopulation()
     */
    protected function crossingAndMutateStrategy(float $extinctRatio)
    {
        $extinctCount = $extinctRatio * $this->getSize();
        for ($idx = 0; $idx < $extinctCount; $idx++) {
            $partnerIdx = $this->getSize() - 1 - $idx;
            $child = $this->factory->createSpawn([$this->population[$idx], $this->population[$partnerIdx]]);
            $child->mutate();
            $this->population[$partnerIdx] = $child;
        }
    }

    protected function onlyBestReproduce(float $extinctRatio): void
    {
        $extinctCount = $extinctRatio * $this->getSize();
        $best = array_slice($this->population, 0, $extinctCount);
        for ($idx = $this->getSize() - $extinctCount; $idx < $this->getSize(); $idx++) {
            $child = $this->factory->createSpawn($best);
            $child->mutate();
            $this->population[$idx] = $child;
        }
    }

    /**
     * Kills the worst fitted PC and replaces them with mutated clones of the top fitted PCs
     * @param float $extinctRatio A ratio between [0,1]
     * @see selectPopulation()
     */
    protected function cloneAndMutateStrategy(float $extinctRatio)
    {
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

    /**
     * Kills the worst fitted PC and replaces them with mutated clones of the best fitted PC
     * @param float $extinctRatio A ratio between [0,1]
     * @see selectPopulation()
     */
    protected function cloneBestAndMutateStrategy(float $extinctRatio)
    {
        $extinctIdx = (1 - $extinctRatio) * $this->getSize();
        // select & mutate
        foreach ($this->population as $idx => $pc) {
            if ($idx >= $extinctIdx) {
                // we clone & mutate the best fit to replace the worst fit
                $npc = clone $this->population[0];
                $npc->mutate();
                $npc->mutate();
                $npc->mutate();
                $this->population[$idx] = $npc;
            }
        }
    }

    protected function evaluateBestFighter(int $round, Mutable $pc1, Mutable $pc2)
    {
        $delta = $pc1->getCost() - $pc2->getCost();

        $key1 = spl_object_hash($pc1);
        $key2 = spl_object_hash($pc2);
        $win = [$key1 => 0, $key2 => 0];
        for ($k = 0; $k < $round; $k++) {
            $pc1->restart();
            $pc2->restart();
            $winner = $this->battle($pc1, $pc2);
            $win[spl_object_hash($winner)]++;
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
    abstract protected function getInitiativeTurn(Fighter $pc1, Fighter $pc2): array;

    /**
     * Battle between 2 PC
     * 
     * @param Fighter $pc1
     * @param Fighter $pc2
     * @return Fighter the winner
     */
    protected function battle(Fighter $pc1, Fighter $pc2): Fighter
    {
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
