<?php

namespace Trismegiste\Genetic\Game\L5r;

/**
 * A util class for rolling dice
 */
class DiceRoller {

    static public function rollD10() {
        $s = 0;
        do {
            $d = rand(1, 10);
            $s += $d;
        } while ($d === 10);

        return $s;
    }

    static public function rollAndKeep($r, $k) {
        $pool = [];
        for ($idx = 0; $idx < $r; $idx++) {
            $pool[$idx] = DiceRoller::rollD10();
        }

        rsort($pool);

        $s = 0;
        for ($idx = 0; $idx < $k; $idx++) {
            $s += $pool[$idx];
        }

        return $s;
    }

}
