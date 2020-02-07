<?php

namespace Trismegiste\Genetic\Game\L5r\Property;

/**
 * A L5R Trait
 */
class RingTrait extends CappedProperty {

    public function getCost() {
        return 4 * $this->attribute * ($this->attribute + 1) / 2 - 12;
    }

    public function __construct($v) {
        parent::__construct($v, 2, 10);
    }

}
