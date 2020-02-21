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

    public function getFitness() {
        return $this->victory;
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
        return $this->roll('dexterity', 'melee', 6);
    }

    protected function getMultipleActionsPenalty() {
        return($this->genome['action']->get() > 1) ? $this->actionCounter + 1 : 0;
    }

    protected function roll(string $attr, string $abil, int $diff) {
        $map = $this->getMultipleActionsPenalty();
        $pool = $this->genome[$attr]->get() + $this->genome[$abil]->get() - $this->getWoundPenalty() - $map;

        return PoolRoller::roll($pool, $diff + $map);
    }

    public function getParry() {
        return$this->roll('dexterity', 'melee', 6);
    }

    public function getDamage(int $delta) {
        $pool = $delta + $this->genome['strength']->get() + $this->weapon;

        return PoolRoller::roll($pool, 6);
    }

    public function canMakeAction(): bool {
        if ($this->isDead()) {
            return false;
        }
        if ($this->actionCounter >= $this->genome['action']->get()) {
            return false;
        }

        return true;
    }

    public function startTurn() {
        $this->actionCounter = 0;
    }

    public function evolve(Fighter $opponent) {
        if (!$this->canMakeAction()) {
            return;
        }

        $attack = $this->getAttack();
        $this->actionCounter++;

        if ($attack > 0) {
            $margin = $opponent->getMarginForAttack($attack);
            if ($margin > 0) {
                $damage = $this->getDamage($margin);
                $opponent->receiveDamage($damage);
            }
        }
    }

    public function getMarginForAttack(int $attack) {
        if (!$this->canMakeAction()) {
            return $attack;
        }

        $parry = $this->getParry();
        $this->actionCounter++;

        return $attack - $parry;
    }

    public function receiveDamage(int $damage) {
        $wound = $damage - PoolRoller::roll($this->genome['stamina']->get(), 6);
        if ($wound > 0) {
            $this->health += $wound;
        }
    }

    public function receiveAttack(Fighter $pc) {
        throw new \LogicException("receiveAttack");
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
