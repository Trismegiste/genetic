<?php

namespace Trismegiste\Genetic\Game\SaWo\Property;

use OutOfBoundsException;
use Trismegiste\Genetic\Game\Property;

/**
 * A property with boundaries
 */
abstract class CappedProperty implements Property {

    protected $attribute;
    protected $minValue;
    protected $maxValue;

    public function __construct(int $v, int $inf, int $sup) {
        if (($v < $inf) || ($v > $sup)) {
            throw new OutOfBoundsException("$inf < $v < $sup");
        }
        $this->attribute = $v;
        $this->minValue = $inf;
        $this->maxValue = $sup;
    }

    public function get() {
        return $this->attribute;
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

    public function __toString() {
        return (string) $this->attribute;
    }

}
