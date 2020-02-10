<?php

namespace Trismegiste\Genetic\Game\SaWo\Property;

/**
 * A SaWo Skill
 */
class Skill extends SaWoTrait {

    protected $attribute;

    public function __construct(Attribute $attr, $dice, $bonus = 0) {
        $this->attribute = $attr;
        parent::__construct($dice, $bonus);
    }

    public function getCost() {
        $attr = $this->attribute->get();
        if ($this->dice <= $attr[0]) {
            $cost = $this->dice / 2 - 2;
        } else {
            $cost = $attr[0] / 2 - 2 + ($this->dice - $attr[0]);
        }
        if ($this->bonus > $attr[1]) {
            $cost += ($this->bonus - $attr[1]) * 2;
        }

        return $cost;
    }

}
