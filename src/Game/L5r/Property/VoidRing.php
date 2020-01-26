<?php

namespace Trismegiste\Genetic\Game\L5r\Property;

/**
 * A L5R void ring
 */
class VoidRing implements \Trismegiste\Genetic\Game\Property {

    private $void;

    public function get() {
        return $this->void;
    }

    public function getCost() {
        return 6 * $this->void * ($this->void + 1) / 2 - 18;
    }

    public function mutate() {
        $this->void += 2 * rand(0, 1) - 1;
        if ($this->void < 2) {
            $this->void = 2;
        }
    }

    public function __construct($v) {
        $this->void = $v;
    }

}
