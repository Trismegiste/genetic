<?php

namespace Trismegiste\Genetic\Game\L5r\Property;

/**
 * A L5R void ring
 */
class VoidRing extends \Trismegiste\Genetic\Game\CappedProperty {

    public function getCost() {
        return 6 * $this->attribute * ($this->attribute + 1) / 2 - 18;
    }

    public function __construct(int $v) {
        parent::__construct($v, 2, 10);
    }

}
