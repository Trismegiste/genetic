<?php

namespace Trismegiste\Genetic\Game\SaWo\Property;

/**
 * A SaWo Attribute
 */
class Attribute extends SaWoTrait {

    public function getCost() {
        return ($this->dice - 4) / 2 + $this->bonus;
    }

}
