<?php

namespace Trismegiste\Genetic\Game\Vda\Property;

use Trismegiste\Genetic\Game\CappedProperty;

/**
 * How many actions per round strategy
 */
class MultipleActionStrat extends CappedProperty {

    public function __construct(int $v) {
        parent::__construct($v, 1, 8);
    }

    public function getCost() {
        return 0;
    }

}
