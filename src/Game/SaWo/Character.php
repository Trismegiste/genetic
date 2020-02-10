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
        $this->wound++;
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
        return 8;
    }

    public function getCost() {
        return 10;
    }

    public function __toString() {
        return "SaWo";
    }

}
