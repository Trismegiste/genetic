<?php

namespace Trismegiste\Genetic\Game\L5r;

/**
 * Ecosystem of tournament for genetic algorithm
 */
class Ecosystem {

    protected $population = [];

    public function __construct($popSize) {
        for ($k = 0; $k < $popSize; $k++) {
            $pc = $this->createPc("L5R", [
                'voidStrat' => Property\VoidStrategy::getRandomStrat(),
                'stance' => Property\Stance::getRandomStrat(),
                'agility' => rand(2, 6),
                'reflexe' => rand(2, 6),
                'earth' => rand(2, 6),
                'kenjutsu' => rand(1, 5),
                'void' => rand(2, 5),
                'strength' => rand(2, 6)
            ]);
            $this->population[] = $pc;
        }
    }

    public function createPc($name, $param = []) {
        return new Character($name, $param);
    }

    public function getSize() {
        return count($this->population);
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

        $report = [];
        foreach ([0, 1, 2, 5, 9] as $idx) {
            $report[] = "$idx - " . $this->population[$idx];
        }

        // select & mutate
        foreach ($this->population as $idx => $pc) {
            if ($idx >= ($extinctRatio * $this->getSize())) {
                // we clone & mutate the best fit to replace the worst fit
                $npc = clone $this->population[$idx - $extinctRatio * $this->getSize()];
                $npc->mutate();
                $this->population[$idx] = $npc;
            }
        }

        return $report;
    }

    protected function tournament($round) {
        foreach ($this->population as $idx1 => $pc1) {
            foreach ($this->population as $idx2 => $pc2) {
                if ($idx2 <= $idx1) {
                    continue;
                }
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
                // many cases are missed : equality. We don't care, we want a threshold effect
            }
        }
    }

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

}
