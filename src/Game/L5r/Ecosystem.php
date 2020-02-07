<?php

namespace Trismegiste\Genetic\Game\L5r;

/**
 * Generic Ecosystem 
 */
abstract class Ecosystem {

    /** @var \Trismegiste\Genetic\Game\Mutable */
    protected $population = [];

    public function getSize() {
        return count($this->population);
    }

    abstract protected function tournament($round);

    /**
     * Battle between 2 PC
     * 
     * @param Character $pc1
     * @param Character $pc2
     * @return Character
     */
    protected function battle(Character $pc1, Character $pc2) {
        $player = [];

        $init1 = $pc1->rollInit();
        $init2 = $pc2->rollInit();

        if ($init1 === $init2) {
            if (rand(1, 2) === 1) {
                $init1++;
            }
        }

        if ($init1 >= $init2) {
            $player = [$pc1, $pc2];
        } else {
            $player = [$pc2, $pc1];
        }

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

    /**
     * Factory
     * 
     * @param type $name
     * @param type $param
     * @return \Trismegiste\Genetic\Game\L5r\Character
     */
    protected function createPc($name, $param = []) {
        return new Character($name, $param);
    }

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

        $this->applyDarwinism($extinctRatio);

        $report = $this->getReport();

        return $report;
    }

    protected function getReport() {
        $report = [];
        foreach ([0, 1, 2, 5, 9] as $idx) {
            $report[] = "$idx - " . $this->population[$idx];
        }

        return $report;
    }

    protected function applyDarwinism($extinctRatio) {
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
