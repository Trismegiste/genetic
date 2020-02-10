<?php

namespace Trismegiste\Genetic\Game\SaWo\Property;

/**
 * A SaWo Skill
 */
class Skill extends SaWoTrait {

    public function getCost() {
        return $this->dice / 4 + $this->bonus;
    }

}
