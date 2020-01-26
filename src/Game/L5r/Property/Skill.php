<?php

namespace Trismegiste\Genetic\Game\L5r\Property;

/**
 * a L5R Skill
 */
class Skill implements \Trismegiste\Genetic\Game\Property {

    private $skill;

    public function get() {
        return $this->skill;
    }

    public function getCost() {
        return $this->skill * ($this->skill + 1) / 2;
    }

    public function mutate() {
        
    }

    public function __construct($v) {
        $this->skill = $v;
    }

}
