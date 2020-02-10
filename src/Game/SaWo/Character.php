<?php

namespace Trismegiste\Genetic\Game\SaWo;

use Trismegiste\Genetic\Game\Mutable;

/**
 * A SaWo character
 */
class Character implements Mutable, Fighter {

    protected $wound = 0;
    protected $fighting = 6;
    protected $victory = 0;
    protected $usedBenny = 0;
    protected $benniesCount = 3;
    protected $genome;
    protected $shaken = false;

    public function __construct($param = []) {
        $default = [
            'fighting' => 6,
            'vigor' => 6,
            'strength' => 6,
            'spirit' => 6
        ];

        // override
        foreach ($param as $key => $val) {
            if (array_key_exists($key, $default)) {
                $default[$key] = $val;
            }
        }

        $this->genome = [
            'fighting' => new Property\SaWoTrait($default['fighting']),
            'vigor' => new Property\SaWoTrait($default['vigor']),
            'strength' => new Property\SaWoTrait($default['strength']),
            'spirit' => new Property\SaWoTrait($default['spirit'])
        ];
    }

    public function __clone() {
        
    }

    public function getFitness() {
        return $this->victory;
    }

    public function mutate() {
        
    }

    public function newGeneration() {
        $this->victory = 0;
    }

    public function incVictory() {
        $this->victory++;
    }

    public function isDead() {
        return $this->wound > 3;
    }

    public function receiveAttack(Fighter $pc) {
        $parry = $this->getParry();
        $attack = $pc->getAttack();

        if ($attack >= $parry) {
            $damage = $pc->getDamage();
            // Raise ?
            if (($attack - $parry) >= 4) {
                $damage += DiceRoller::rollExplodingDie(6);
            }
            // compare damage and toughness
            if ($damage >= $this->getToughness()) {
                $delta = floor(($damage - $this->getToughness()) / 4);

                if ($delta === 0) {
                    if ($this->shaken) {
                        $this->wound++;
                    } else {
                        $this->shaken = true;
                    }
                } else {
                    $this->wound += $delta;
                    $this->shaken = true;
                }
            }
        }
    }

    public function getDamage() {
        return DiceRoller::rollExplodingDie(6) + DiceRoller::rollExplodingDie(6);
    }

    public function restart() {
        $this->wound = 0;
        $this->usedBenny = 0;
    }

    public function getVictory() {
        return $this->victory;
    }

    public function getParry() {
        return 8;
    }

    public function getToughness() {
        return $this->genome['vigor']->getDifficulty();
    }

    public function getCost() {
        return 10;
    }

    public function __toString() {
        return "SaWo";
    }

    public function getAttack() {
        return DiceRoller::rollJoker($this->genome['fighting']);
    }

}
