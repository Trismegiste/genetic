<?php

namespace Trismegiste\Genetic\Game\L5r;

use Trismegiste\Genetic\Game\Character as CharInt;
use Trismegiste\Genetic\Game\Fighter;

/**
 * A L5R character
 */
class Character implements CharInt, Fighter {

    protected $name;
    protected $attack = [3, 3]; // [trait, competence]
    protected $earthRing = 3;
    protected $damage = [2, 4];  // 6g2
    protected $voidRing = 3;
    protected $wounds = 0;
    protected $usedVoidPoint = 0;
    protected $reflexeTrait = 3;
    protected $levelPenalty = [3, 5, 10, 15, 20, 40];
    protected $voidStrategy; // attack, armor, soak
    protected $insightRank = 1;
    protected $winningCount = 0;
    protected $generation = 0;

    public function __construct($n, $voidStrat = 'attack') {
        $this->name = $n;
        $this->voidStrategy = $voidStrat;
    }

    public function getVoidStrat() {
        return $this->voidStrategy;
    }

    public function addWounds($val) {
        if ($this->voidStrategy === 'soak') {
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
        return $this->wounds > (19 * $this->earthRing);
    }

    public function receiveAttack(Fighter $f) {
        $att = $f->getAttack();
        $tn = 5 * ($this->reflexeTrait + 1);

        if ($this->voidStrategy === 'armor') {
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
        $roll = $this->attack[0] + $this->attack[1];
        $keep = $this->attack[0];

        if ($this->voidStrategy === 'attack') {
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

    protected function hasVoidPoint() {
        return $this->usedVoidPoint < $this->voidRing;
    }

    protected function useVoidPoint() {
        $this->usedVoidPoint++;
    }

    public function getWoundPenalty() {
        if ($this->wounds <= (5 * $this->earthRing)) {
            return 0;
        }
        $levelAfterFirst = floor(($this->wounds - 5 * $this->earthRing) / 2);

        if ($levelAfterFirst < count($this->levelPenalty)) {
            return $this->levelPenalty[$levelAfterFirst];
        } else {
            return 1000;
        }
    }

    public function rollInit() {
        return DiceRoller::rollAndKeep($this->reflexeTrait + $this->insightRank, $this->reflexeTrait);
    }

    public function incVictory() {
        $this->winningCount++;
    }

    public function getWinningCount() {
        return $this->winningCount;
    }

}
