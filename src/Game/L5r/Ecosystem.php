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
        // select & mutate
        foreach ($this->population as $idx => $pc) {
            if ($idx >= ($extinctRatio * $this->getSize())) {
                // we clone & mutate the best fit to replace the worst fit
                $npc = clone $this->population[$idx - $extinctRatio * $this->getSize()];
                $npc->mutate();
                $this->population[$idx] = $npc;
            }
        }
    }

}
