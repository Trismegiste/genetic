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
    protected $levelPenalty = [3, 5, 10, 15, 20, 40, 1000];
    protected $voidStrategy; // attack, armor, soak
    protected $insightRank = 1;
    protected $winningCount = 0;
    protected $generation = 0;

    public function __construct($n, $voidStrat = 'attack') {
        $this->name = $n;
        $this->voidStrategy = $voidStrat;
    }

    public function addWounds($val) {
        if ($this->voidStrategy === 'soak') {
            if ($this->hasVoidPoint()) {
                $this->usedVoidPoint();
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
                $this->usedVoidPoint();
                $tn += 10;
            }
        }

        return $att >= $tn;
    }

    public function restart() {
        $this->wounds = 0;
        $this->usedVoidPoint = 0;
    }

    public function getAttack() {
        
    }

    public function getDamage() {
        
    }

    protected function hasVoidPoint() {
        return $this->usedVoidPoint < $this->voidRing;
    }

    protected function useVoidPoint() {
        $this->usedVoidPoint++;
    }

}
