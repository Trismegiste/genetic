<?php

namespace Trismegiste\Genetic\Game\L5r\Property;

/**
 * A L5R Trait
 */
class RingTrait implements \Trismegiste\Genetic\Game\Property {

    private $attribute;

    public function get() {
        return $this->attribute;
    }

    public function getCost() {
        return 4 * $this->attribute * ($this->attribute + 1) / 2;
    }

    public function mutate() {
        
    }

    public function __construct($v) {
        $this->attribute = $v;
    }

}
