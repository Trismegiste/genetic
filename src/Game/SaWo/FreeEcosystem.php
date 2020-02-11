<?php

namespace Trismegiste\Genetic\Game\SaWo;

use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\Fighter;
use Trismegiste\Genetic\Game\SaWo\Character;
use Trismegiste\Genetic\Game\SaWo\Property\BennyStrat;

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
                'benny' => BennyStrat::getRandomStrat(),
                'block' => mt_rand(0, 2),
                'trademark' => mt_rand(0, 2)
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

    protected function getInitiativeTurn(Fighter $pc1, Fighter $pc2) {
        $player = [];
        if (mt_rand(0, 1)) {
            $player = [$pc1, $pc2];
        } else {
            $player = [$pc2, $pc1];
        }

        return $player;
    }

}
