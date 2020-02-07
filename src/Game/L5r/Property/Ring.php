<?php

namespace Trismegiste\Genetic\Game\L5r\Property;

/**
 * A L5R Ring
 */
class Ring extends CappedProperty {

    public function getCost() {
        return 8 * $this->attribute * ($this->attribute + 1) / 2 - 24;
    }

    public function __construct($v) {
        parent::__construct($v, 2, 10);
    }

}
