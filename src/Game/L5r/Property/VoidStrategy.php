<?php

namespace Trismegiste\Genetic\Game\L5r\Property;

/**
 * a strategy for Void points
 */
class VoidStrategy implements \Trismegiste\Genetic\Game\Property {

    private $strat;  // attack, armor, soak

    public function get() {
        return $this->strat;
    }

    public function getCost() {
        return 0;
    }

    public function mutate() {
        
    }

    public function __construct($v) {
        $this->strat = $v;
    }

}
