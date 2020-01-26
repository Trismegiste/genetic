<?php

namespace Trismegiste\Genetic\Game\L5r\Property;

/**
 * A L5R void ring
 */
class VoidRing implements \Trismegiste\Genetic\Game\Property {

    private $void;

    public function get() {
        return $this->void;
    }

    public function getCost() {
        return 6 * $this->attribute * ($this->attribute + 1) / 2;
    }

    public function mutate() {
        
    }

    public function __construct($v) {
        $this->void = $v;
    }

}
