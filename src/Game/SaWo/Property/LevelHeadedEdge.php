<?php

namespace Trismegiste\Genetic\Game\SaWo\Property;

use Trismegiste\Genetic\Game\CappedProperty;

/**
 * LevelHeaded and Improved Edges
 */
class LevelHeadedEdge extends CappedProperty {

    public function __construct(int $v) {
        parent::__construct($v, 0, 2);
    }

    //put your code here
    public function getCost() {
        return $this->attribute * 2;
    }

    public function drawCard(): int {
        $draw = [];
        for ($k = 0; $k <= $this->attribute; $k++) { // default : one card
            $draw[] = mt_rand(1, 54);
        }

        return max($draw);
    }

}
