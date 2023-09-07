<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Trismegiste\Genetic\Game\SaWo\Property;

/**
 * Multi attack strategy
 */
class MultiAttackStrat extends \Trismegiste\Genetic\Game\CappedProperty
{

    public function __construct(int $v)
    {
        parent::__construct($v, 1, 3);
    }

    public function getCost()
    {
        return 0;
    }

    public function getPenalty(): int
    {
        return -2 * ($this->attribute - 1);
    }

}
