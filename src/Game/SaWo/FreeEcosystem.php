<?php

namespace Trismegiste\Genetic\Game\SaWo;

use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\Mutable;
use Trismegiste\Genetic\Game\SaWo\Character;

/**
 * A Free evolution for SaWo
 */
class FreeEcosystem extends DarwinWorld {

    protected function createPopulation(int $popSize): array {
        $pop = [];
        for ($k = 0; $k < $popSize; $k++) {
            $pc = new Character([
                'strength' => 2 * mt_rand(2, 6),
                'vigor' => 2 * mt_rand(2, 6),
                'spirit' => 2 * mt_rand(2, 6),
                'fighting' => 2 * mt_rand(2, 6),
                'agility' => 2 * mt_rand(2, 6),
                'benny' => Property\BennyStrat::getRandomStrat()
            ]);
            $pop[] = $pc;
        }

        return $pop;
    }

    public function getReport() {
        $report = [];
        foreach ([0, 1, 2, 5, 9] as $idx) {
            $report[] = "$idx - " . $this->population[$idx];
        }

        return [
            'grafx' => array_map(function($pc) {
                        return ['x' => $pc->getCost(), 'y' => $pc->getVictory()];
                    }, $this->population),
            'text' => $report
        ];
    }

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

    protected function getInitiativeTurn(Character $pc1, Character $pc2) {
        if (mt_rand(0, 1)) {
            $player = [$pc1, $pc2];
        } else {
            $player = [$pc2, $pc1];
        }

        return $player;
    }

    /**
     * Battle between 2 PC
     * 
     * @param Character $pc1
     * @param Character $pc2
     * @return Character the winner
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
        // many cases are missed : equality. We don't care, we want a threshold effect
    }

}
