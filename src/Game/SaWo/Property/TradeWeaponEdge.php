<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Trismegiste\Genetic\Game\SaWo\Property;

/**
 * Description of TradeWeaponEdge
 *
 * @author flo
 */
class TradeWeaponEdge extends CappedProperty {

    public function __construct(int $v) {
        parent::__construct($v, 0, 2);
    }

    public function getCost() {
        return $this->attribute * 2;
    }

}
