<?php

namespace Trismegiste\Genetic\Game\SaWo\Property;

/**
 * A SaWo Skill
 */
class Skill extends SaWoTrait {

    protected $attribute;

    public function __construct(Attribute $attr, int $dice) {
        $this->attribute = $attr;
        parent::__construct($dice);
    }

    public function getCost() {
        $attr = $this->attribute->get();
        if ($this->dice <= $attr) {
            $cost = $this->dice / 2 - 2;
        } else {
            $cost = $attr / 2 - 2 + ($this->dice - $attr);
        }

        return $cost;
    }

}
