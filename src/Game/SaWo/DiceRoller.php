<?php

namespace Trismegiste\Genetic\Game\SaWo;

/**
 * A SaWo dice roller
 */
class DiceRoller {

    protected static function rollExplodingDie($face) {
        $s = 0;
        do {
            $d = mt_rand(1, $face);
            $s += $d;
        } while ($d === $face);

        return $s;
    }

    public static function roll(Property\SaWoTrait $aTrait) {
        $dice = $aTrait->get();

        return self::rollExplodingDie($dice[0]) + $dice[1];
    }

    public static function rollJoker(Property\SaWoTrait $aTrait, $joker = 6) {
        $dice = $aTrait->get();

        return $dice[1] + max([self::rollExplodingDie($dice[0]), self::rollExplodingDie($joker)]);
    }

}
