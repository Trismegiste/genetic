<?php

/*
 * Genetic
 */

namespace Trismegiste\Genetic\Game\SaWo\Property;

/**
 * Edge Nerve Of Steel
 */
class NerveOfSteelEdge extends \Trismegiste\Genetic\Game\CappedProperty
{

    public function __construct(int $v)
    {
        parent::__construct($v, 0, 2);
    }

    public function getCost()
    {
        return $this->attribute * 2;
    }

}
