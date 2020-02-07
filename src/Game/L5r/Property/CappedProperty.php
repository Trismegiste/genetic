<?php

namespace Trismegiste\Genetic\Game\L5r\Property;

use Trismegiste\Genetic\Game\Property;

/**
 * A generic property with bounderies
 */
abstract class CappedProperty implements Property {

    protected $attribute;
    protected $minValue;
    protected $maxValue;

    public function __construct($v, $inf, $sup) {
        $this->attribute = $v;
        $this->minValue = $inf;
        $this->maxValue = $sup;
    }

    public function mutate() {
        if ($this->attribute === $this->minValue) {
            $this->attribute++;
        } else if ($this->attribute === $this->maxValue) {
            $this->attribute--;
        } else {
            $this->attribute += 2 * rand(0, 1) - 1;
        }
    }

    public function get() {
        return $this->attribute;
    }

}