<?php

namespace Trismegiste\Genetic\Game\SaWo\Property;

/**
 * Block Edges
 */
class BlockEdge extends CappedProperty {

    protected $bonus;

    public function __construct(int $v) {
        parent::__construct($v, 0, 2);
    }

    public function getCost() {
        return $this->attribute * 2;
    }

}
