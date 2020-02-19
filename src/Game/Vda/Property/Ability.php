<?php

namespace Trismegiste\Genetic\Game\Vda\Property;

use Trismegiste\Genetic\Game\CappedProperty;

/**
 * An Ability for VDA
 */
class Ability extends CappedProperty {

    public function __construct(int $v) {
        parent::__construct($v, 1, 5);
    }

    public function getCost() {
        return $this->attribute * ($this->attribute - 1);
    }

}
