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
            'fighting' => new Property\Skill($default['fighting']),
            'vigor' => new Property\Attribute($default['vigor']),
            'strength' => new Property\Attribute($default['strength']),
            'spirit' => new Property\Attribute($default['spirit'])
        ];
    }

    public function __clone() {
        $tmp = [];
        foreach ($this->genome as $key => $gene) {
            $tmp[$key] = clone $gene;
        }
        $this->genome = $tmp;
    }

    public function getFitness() {
        return $this->victory;
    }

    public function mutate() {
        $card = count($this->genome);
        foreach ($this->genome as $key => $gene) {
            if (0 === mt_rand(0, $card - 1)) {
                $gene->mutate();
            }
        }
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
        return DiceRoller::roll($this->genome['strength']) + DiceRoller::rollExplodingDie(6);
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
        $cost = 0;
        foreach ($this->genome as $gene) {
            $cost += $gene->getCost();
        }

        return $cost;
    }

    public function __toString() {
        $compil = '';
        foreach ($this->genome as $key => $gene) {
            $compil .= $key . ':' . $gene . ' ';
        }

        return $compil . 'win:' . $this->victory . ' cost:' . $this->getCost();
    }

    public function getAttack() {
        return DiceRoller::rollJoker($this->genome['fighting']);
    }

}
