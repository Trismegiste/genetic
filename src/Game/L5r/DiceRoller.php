<?php

namespace Trismegiste\Genetic\Game\L5r;

/**
 * A util class for rolling dice
 */
class DiceRoller {

    /**
     * Rolls one exploding d10
     * 
     * @return int
     */
    static public function rollD10(): int {
        $s = 0;
        do {
            $d = mt_rand(1, 10);
            $s += $d;
        } while ($d === 10);

        return $s;
    }

    /**
     * Rolls & Keeps multiple dice
     * 
     * @param int $r
     * @param int $k
     * @return int the sum
     */
    static public function rollAndKeep(int $r, int $k): int {
        $delta = 0;
        // cap r&k > 10
        if (($r >= 10) && ($k >= 10)) {
            $delta = 2 * ($r - 10) + 2 * ($k - 10);
            $r = 10;
            $k = 10;
        }
        // cap for roll
        if ($r > 11) {
            $k += floor(($r - 10) / 2);
            $r = 10;
            if ($k > 10) {
                $delta = 2 * ($k - 10);
                $k = 10;
            }
        }

        // cap for keep
        if ($k > $r) {
            $k = $r;
        }

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
