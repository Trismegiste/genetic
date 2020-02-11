<?php

namespace Trismegiste\Genetic\Game\SaWo\Property;

use Trismegiste\Genetic\Game\Property;

/**
 * A SaWo Trait
 * 
 * Note : yeah I know it's "die" not "dice", but die is reserved keyword
 */
abstract class SaWoTrait implements Property {

    const diceChoice = ['4', '6', '8', '10', '12'];

    protected $dice;

    public function __construct(int $dice) {
        if (!in_array($dice, self::diceChoice)) {
            throw new \DomainException("The die d$dice is invalid");
        }
        $this->dice = $dice;
    }

    public function get() {
        return $this->dice;
    }

    public function mutate() {
        if ($this->dice === 4) {
            $this->dice += 2;
        } else if ($this->dice === 12) {
            $this->dice -= 2;
        } else {
            $this->dice += 4 * mt_rand(0, 1) - 2;
        }
    }

    public function getDifficulty() {
        return (int) ($this->dice / 2 + 2);
    }

    public function __toString() {
        return 'd' . $this->dice;
    }

}
