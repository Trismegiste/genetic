<?php

namespace Trismegiste\Genetic\Game\Vda\Property;

use Trismegiste\Genetic\Game\CappedProperty;

/**
 * An attribute for VDA
 */
class Attribute extends CappedProperty {

    public function __construct(int $v) {
        parent::__construct($v, 1, 5);
    }

    public function getCost() {
        return 2 * $this->attribute * ($this->attribute - 1);
    }

}
