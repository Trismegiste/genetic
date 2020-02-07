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
        return 4 * $this->attribute * ($this->attribute + 1) / 2 - 12;
    }

    public function mutate() {
        $this->attribute += 2 * rand(0, 1) - 1;
        if ($this->attribute < 2) {
            $this->attribute = 2;
        }
        if ($this->attribute > 10) {
            $this->attribute = 10;
        }
    }

    public function __construct($v) {
        $this->attribute = $v;
    }

}
