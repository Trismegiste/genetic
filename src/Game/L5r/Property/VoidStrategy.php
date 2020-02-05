<?php

namespace Trismegiste\Genetic\Game\L5r\Property;

/**
 * a strategy for Void points
 */
class VoidStrategy implements \Trismegiste\Genetic\Game\Property {

    private $strat;
    static private $choice = ['attack', 'armor', 'soak', 'damage'];

    public function get() {
        return $this->strat;
    }

    public function getCost() {
        return 0;
    }

    public function mutate() {
        $this->strat = self::getRandomStrat();
    }

    public function __construct($v) {
        if (!in_array($v, self::$choice)) {
            throw new \OutOfBoundsException($v);
        }
        $this->strat = $v;
    }

    static public function getRandomStrat() {
        return self::$choice[rand(0, count(self::$choice) - 1)];
    }

}
