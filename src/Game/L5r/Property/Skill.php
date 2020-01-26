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
        return $this->skill * ($this->skill + 1) / 2 - 1;
    }

    public function mutate() {
        $this->skill += 2 * rand(0, 1) - 1;
        if ($this->skill < 1) {
            $this->skill = 1;
        }
        if ($this->skill > 10) {
            $this->skill = 10;
        }
    }

    public function __construct($v) {
        $this->skill = $v;
    }

}
