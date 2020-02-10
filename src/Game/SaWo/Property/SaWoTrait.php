<?php

namespace Trismegiste\Genetic\Game\SaWo\Property;

use Trismegiste\Genetic\Game\Property;

/**
 * A SaWo Trait
 * 
 * Note : yeah I know it's "die" not "dice", but die is reserved keyword
 */
class SaWoTrait implements Property {

    const diceChoice = ['4', '6', '8', '10', '12'];

    protected $dice;
    protected $bonus;

    public function __construct($dice, $bonus = 0) {
        if (!in_array($dice, self::diceChoice)) {
            throw new \DomainException("The die d$dice is invalid");
        }
        if ($bonus < 0) {
            throw new \OutOfBoundsException($bonus);
        }
        if (($bonus > 0) && ($dice !== 12)) {
            throw new \OutOfBoundsException("Non-zero bonus is only valid with d12");
        }
        $this->dice = $dice;
        $this->bonus = $bonus;
    }

    public function get() {
        return [$this->dice, $this->bonus];
    }

    public function getCost() {
        return 0;
    }

    public function mutate() {
        if ($this->dice === 4) {
            $this->dice += 2;
        } else {
            $direction = 2 * mt_rand(0, 1) - 1;
            if ($this->dice !== 12) {
                $this->dice += 2 * $direction;
            } else { // d12
                if (($this->bonus === 0) && ($direction < 0)) {
                    $this->dice = 10;
                } else {
                    $this->bonus += $direction;
                }
            }
        }
    }

    public function getDifficulty() {
        return $this->dice / 2 + 2 + floor($this->bonus / 2);
    }

}
