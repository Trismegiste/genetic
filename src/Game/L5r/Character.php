<?php

namespace Trismegiste\Genetic\Game\L5r;

use Trismegiste\Genetic\Game\Character as CharInt;
use Trismegiste\Genetic\Game\Fighter;

/**
 * A L5R character
 */
class Character implements CharInt, Fighter {

    protected $name;
    protected $genome = [];
    protected $damage = [2, 4];  // 6g2
    protected $wounds = 0;
    protected $usedVoidPoint = 0;
    protected $levelPenalty = [3, 5, 10, 15, 20, 40];
    protected $insightRank = 1;
    protected $winningCount = 0;
    protected $generation = 0;

    public function __construct($n, $voidStrat = 'attack') {
        $this->name = $n;
        $this->genome = [
            'agility' => new Property\RingTrait(3),
            'kenjutsu' => new Property\Skill(3),
            'void' => new Property\VoidRing(3),
            'reflexe' => new Property\RingTrait(3),
            'earth' => new Property\Ring(3),
            'voidStrat' => new Property\VoidStrategy($voidStrat)
        ];
    }

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

    public function getName() {
        return $this->name;
    }

    public function isDead() {
        return $this->wounds > (19 * $this->genome['earth']->get());
    }

    public function receiveAttack(Fighter $f) {
        $att = $f->getAttack();
        $tn = 5 * ($this->genome['reflexe']->get() + 1);

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

        return DiceRoller::rollAndKeep($roll, $keep) - $this->getWoundPenalty();
    }

    public function getDamage() {
        return DiceRoller::rollAndKeep($this->damage[0] + $this->damage[1], $this->damage[0]);
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

    public function incVictory() {
        $this->winningCount++;
    }

    public function getWinningCount() {
        return $this->winningCount;
    }

}
