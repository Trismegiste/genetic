<?php

namespace Trismegiste\Genetic\Game\SaWo\Property;

use Trismegiste\Genetic\Game\CappedProperty;

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
