<?php

namespace Trismegiste\Genetic\Game\Vda;

use Trismegiste\Genetic\Game\Fighter;
use Trismegiste\Genetic\Game\MutableFighter;

/**
 * A VDA Character
 */
class Character extends MutableFighter {

    const woundPenalty = [0 => 0, 1 => 0, 2 => -1, 3 => -1, 4 => -2, 5 => -2, 6 => -5];

    protected $health = 0;
    protected $weapon = 2;
    protected $actionCounter = 0;
    protected $actionPerRd = 1;

    public function getFitness() {
        return $this->victory;
    }

    public function hasActionLeft() {
        return $this->actionCounter < $this->actionPerRd;
    }

    protected function getWoundPenalty() {
        return ($this->health >= 7) ? -1000 : self::woundPenalty[$this->health];
    }

    public function isDead() {
        return ($this->health >= 7);
    }

    public function rollInitiative() {
        return mt_rand(1, 10) + $this->genome['wits']->get() + $this->genome['dexterity']->get() - $this->getWoundPenalty();
    }

    public function newGeneration() {
        $this->victory = 0;
    }

    public function getAttack() {
        return $this->hasActionLeft() ? $this->roll('dexterity', 'melee', 6) : 0;
    }

    protected function roll(string $attr, string $abil, int $diff) {
        $pool = $this->genome[$attr]->get() + $this->genome[$abil]->get() - $this->getWoundPenalty();

        return PoolRoller::roll($pool, $diff);
    }

    public function getParry() {
        return $this->hasActionLeft() ? $this->roll('dexterity', 'melee', 6) : 0;
    }

    public function getDamage(int $delta) {
        $pool = $delta + $this->genome['strength']->get() + $this->weapon;

        return PoolRoller::roll($pool, 6);
    }

    public function receiveAttack(Fighter $pc) {
        $attack = $pc->getAttack();
        if ($attack > 0) {
            $delta = $attack - $this->getParry();
            if ($delta > 0) {
                $damage = $pc->getDamage($delta);
                $wound = $damage - PoolRoller::roll($this->genome['stamina']->get(), 6);
                if ($wound > 0) {
                    $this->health += $wound;
                }
            }
        }
    }

    public function restart() {
        $this->health = 0;
        $this->actionCounter = 0;
    }

    public function __toString() {
        $compil = '';
        foreach ($this->genome as $key => $gene) {
            $compil .= $key . ':' . $gene . ' ';
        }

        return $compil . 'win:' . $this->victory . ' cost:' . $this->getCost();
    }

}
