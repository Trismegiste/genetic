<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Trismegiste\Genetic\Game\SaWo\Property;

/**
 * Description of AttackStrat
 *
 * @author flo
 */
class AttackStrat implements \Trismegiste\Genetic\Game\Property {

    const choice = ['standard', 'wild'];

    protected $strategy;

    public function __construct(string $s) {
        if (!in_array($s, self::choice, true)) {
            throw new \DomainException($s);
        }
        $this->strategy = $s;
    }

    public function get() {
        return $v;
    }

    public function getCost() {
        return 0;
    }

    public function mutate() {
        $this->strategy = ($this->strategy === self::choice[0]) ? self::choice[1] : self::choice[0];
    }

    public static function getRandomStrat() {
        return self::choice[mt_rand(0, count(self::choice) - 1)];
    }

    public function __toString() {
        return $this->strategy;
    }

}
