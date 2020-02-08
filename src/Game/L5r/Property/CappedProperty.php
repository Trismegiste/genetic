<?php

namespace Trismegiste\Genetic\Game\L5r\Property;

use Trismegiste\Genetic\Game\Property;

/**
 * A generic property with boundaries
 */
abstract class CappedProperty implements Property {

    protected $attribute;
    protected $minValue;
    protected $maxValue;

    public function __construct($v, $inf, $sup) {
        if (($v < $inf) || ($v > $sup)) {
            throw new \OutOfBoundsException("$inf < $v < $sup");
        }
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
            $this->attribute += 2 * mt_rand(0, 1) - 1;
        }
    }

    public function get() {
        return $this->attribute;
    }

}
