<?php

namespace Trismegiste\Genetic\Game\L5r\Property;

/**
 * a L5R Skill
 */
class Skill extends \Trismegiste\Genetic\Game\CappedProperty {

    public function getCost() {
        return $this->attribute * ($this->attribute + 1) / 2 - 1;
    }

    public function __construct(int $v) {
        parent::__construct($v, 1, 10);
    }

}
