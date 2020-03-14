<?php

namespace Trismegiste\Genetic\Game\SaWo;

/**
 * A SaWo dice roller
 */
class DiceRoller {

    public static function rollExplodingDie(int $face): int {
        $s = 0;
        do {
            $d = mt_rand(1, $face);
            $s += $d;
        } while ($d === $face);

        return $s;
    }

    public static function roll(Property\SaWoTrait $aTrait): int {
        return self::rollExplodingDie($aTrait->get());
    }

    public static function rollJoker(Property\SaWoTrait $aTrait, $joker = 6): int {
        return max([self::rollExplodingDie($aTrait->get()), self::rollExplodingDie($joker)]);
    }

}
