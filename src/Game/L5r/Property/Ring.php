<?php

namespace Trismegiste\Genetic\Game\L5r\Property;

/**
 * A L5R Ring
 */
class Ring implements \Trismegiste\Genetic\Game\Property {

    private $ring;

    public function get() {
        return $this->ring;
    }

    public function getCost() {
        return 8 * $this->ring * ($this->ring + 1) / 2 - 24;
    }

    public function mutate() {
        $this->ring += 2 * rand(0, 1) - 1;
        if ($this->ring < 2) {
            $this->ring = 2;
        }
    }

    public function __construct($v) {
        $this->ring = $v;
    }

}
