<?php

namespace Trismegiste\Genetic\Game\Vda\Property;

use Trismegiste\Genetic\Game\CappedProperty;

/**
 * A VDA discipline
 */
class Discipline extends CappedProperty {

    public function __construct(int $v) {
        parent::__construct($v, 0, 5);
    }

    public function getCost() {

        return ($this->attribute > 0) ? 10 + 5 * $this->attribute * ($this->attribute - 1) / 2 : 0;
    }

}
