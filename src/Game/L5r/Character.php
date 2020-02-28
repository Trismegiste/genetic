<?php

namespace Trismegiste\Genetic\Game\L5r;

use InvalidArgumentException;
use Trismegiste\Genetic\Game\Fighter;
use Trismegiste\Genetic\Game\MutableFighter;
use Trismegiste\Genetic\Game\Property;

/**
 * A L5R character
 */
class Character extends MutableFighter {

    protected $weaponRoll = 4; // + strength
    protected $weaponKeep = 2;
    protected $wounds = 0;
    protected $usedVoidPoint = 0;
    protected $levelPenalty = [3, 5, 10, 15, 20, 40];
    protected $insightRank = 1;

    public function getVoidStrat() {
        return $this->genome['voidStrat']->get();
    }

    public function addWounds($val) {
        if ($this->getVoidStrat() === 'soak') {
            if ($this->hasVoidPoint()) {
                $this->useVoidPoint();
                $val -= 10;
            }
        }

        $this->wounds += $val;
    }

    public function isDead() {
        return $this->wounds > (19 * $this->genome['earth']->get());
    }

    public function getArmorTN() {
        $tn = 5 * ($this->genome['reflexe']->get() + 1);

        if ($this->genome['stance']->get() === 'full') {
            $tn -= 10;
        }

        return $tn;
    }

    /**
     * Receive an attack from an opponent
     * WARNING : this object is modified if Void Point is used
     * @return int
     */
    public function receiveAttack(Fighter $f) {
        $att = $f->getAttack();
        $tn = $this->getArmorTN();

        if ($this->getVoidStrat() === 'armor') {
            if ($this->hasVoidPoint()) {
                $this->useVoidPoint();
                $tn += 10;
            }
        }

        if ($att >= $tn) {
            $dam = $f->getDamage();
            $this->addWounds($dam);
        }
    }

    public function restart() {
        $this->wounds = 0;
        $this->usedVoidPoint = 0;
    }

    /**
     * Gets an attack roll
     * WARNING : this object is modified if Void Point is used
     * @return int
     */
    public function getAttack() {
        $roll = $this->genome['agility']->get() + $this->genome['kenjutsu']->get();
        $keep = $this->genome['agility']->get();

        if ($this->getVoidStrat() === 'attack') {
            if ($this->hasVoidPoint()) {
                $this->useVoidPoint();
                $roll++;
                $keep++;
            }
        }

        if ($this->genome['stance']->get() === 'full') {
            $roll += 2;
            $keep++;
        }

        return DiceRoller::rollAndKeep($roll, $keep) - $this->getWoundPenalty();
    }

    /**
     * Gets a damage roll
     * WARNING : this object is modified if Void Point is used
     * @return int
     */
    public function getDamage() {
        $keep = $this->weaponKeep;
        $roll = $this->weaponRoll + $this->genome['strength']->get() + $keep;

        if ($this->genome['kenjutsu']->get() >= 3) {
            $roll++;
        }

        if ($this->getVoidStrat() === 'damage') {
            if ($this->hasVoidPoint()) {
                $this->useVoidPoint();
                $roll++;
                $keep++;
            }
        }

        return DiceRoller::rollAndKeep($roll, $keep);
    }

    public function hasVoidPoint() {
        return $this->usedVoidPoint < $this->genome['void']->get();
    }

    public function useVoidPoint() {
        $this->usedVoidPoint++;
    }

    public function getWoundPenalty() {
        if ($this->wounds <= (5 * $this->genome['earth']->get())) {
            return 0;
        }
        $levelAfterFirst = floor(($this->wounds - 5 * $this->genome['earth']->get()) / 2);

        if ($levelAfterFirst < count($this->levelPenalty)) {
            return $this->levelPenalty[$levelAfterFirst];
        } else {
            return 1000;
        }
    }

    public function rollInit() {
        return DiceRoller::rollAndKeep($this->genome['reflexe']->get() + $this->insightRank, $this->genome['reflexe']->get());
    }

    public function getFitness() {
        return $this->getVictory();
    }

    public function newGeneration() {
        $this->victory = 0;
    }

    public function __toString() {
        $compil = '';
        foreach ($this->genome as $key => $gene) {
            $compil .= $key . ':' . $gene->get() . ' ';
        }

        return $compil . 'win:' . $this->victory . ' cost:' . $this->getCost();
    }

}
