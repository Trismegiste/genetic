<?php

namespace Trismegiste\Genetic\Game\L5r\Property;

/**
 * Stance of a PC
 */
class Stance implements \Trismegiste\Genetic\Game\Property {

    private $strat;
    static private $choice = ['full', 'standard'];

    public function __construct($v) {
        if (!in_array($v, self::$choice)) {
            throw new \OutOfBoundsException($v);
        }
        $this->strat = $v;
    }

    public function get() {
        return $this->strat;
    }

    public function getCost() {
        return 0;
    }

    public function mutate() {
        $this->strat = ($this->strat === self::$choice[0]) ? self::$choice[1] : self::$choice[0];
    }

    static public function getRandomStrat() {
        return self::$choice[mt_rand(0, count(self::$choice) - 1)];
    }

}
