<?php

namespace Trismegiste\Genetic\Game\SaWo\Property;

/**
 * Block Edges
 */
class BlockEdge implements \Trismegiste\Genetic\Game\Property {

    protected $bonus;

    public function __construct(int $v) {
        $this->bonus = $v;
    }

    public function get() {
        return $this->bonus;
    }

    public function getCost() {
        return $this->bonus * 2;
    }

    public function mutate() {
        if ($this->bonus === 0) {
            $this->bonus++;
        } else if ($this->bonus === 2) {
            $this->bonus--;
        } else {
            $this->bonus += 2 * mt_rand(0, 1) - 1;
        }
    }

    public function __toString() {
        return (string) $this->bonus;
    }

}
