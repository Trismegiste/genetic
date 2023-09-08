<?php

namespace Trismegiste\Genetic\Game\SaWo;

use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\Fighter;

/**
 * A Free evolution for SaWo
 */
class FreeEcosystem extends DarwinWorld
{

    protected function getInitiativeTurn(Fighter $pc1, Fighter $pc2): array
    {
        $init1 = $pc1->getInitiative();
        $init2 = $pc2->getInitiative();

        if ($init1 > $init2) {
            return [$pc1, $pc2];
        } else if ($init1 < $init2) {
            return [$pc2, $pc1];
        }

        if (mt_rand(0, 1)) {
            return [$pc1, $pc2];
        } else {
            return [$pc2, $pc1];
        }
    }

    private function selectBestPerCost(float $extinctRatio): void
    {
        $extinctCount = $extinctRatio * $this->getSize();
        $bestCost = $this->population[0]->getCost();
        $best = [];
        foreach ($this->population as $fighter) {
            $cost = $fighter->getCost();
            if ($cost >= $bestCost) {
                if (!key_exists($cost, $best)) {
                    $best[$cost] = $fighter;
                } else {
                    if ($fighter->getFitness() > $best[$cost]->getFitness()) {
                        $best[$cost] = $fighter;
                    }
                }
            }
            if (count($best) > $extinctCount) {
                break;
            }
        }
        $best = array_values($best);

        for ($idx = $this->getSize() - $extinctCount; $idx < $this->getSize(); $idx++) {
            $child = $this->factory->createSpawn($best);
            $child->mutate();
            $this->population[$idx] = $child;
        }
    }

    protected function selectPopulation(float $extinctRatio)
    {
        $this->selectBestPerCost($extinctRatio);
    }

    protected function battle(Fighter $pc1, Fighter $pc2): Fighter
    {
        while (!$pc1->isDead() && !$pc2->isDead()) {
            $player = $this->getInitiativeTurn($pc1, $pc2);
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
