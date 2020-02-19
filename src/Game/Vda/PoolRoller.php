<?php

namespace Trismegiste\Genetic\Game\Vda;

/**
 * Roll a dice pool
 */
class PoolRoller {

    static public function roll(int $rating, int $difficulty) {
        if ($rating <= 0) {
            return 0;
        }

        $success = 0;
        for ($k = 0; $k < $rating; $k++) {
            $d = mt_rand(1, 10);
            if ($d >= $difficulty) {
                $success++;
                continue;
            }
            if ($d === 1) {
                $success--;
            }
        }

        return $success;
    }

}
