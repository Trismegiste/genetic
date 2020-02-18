<?php

namespace Trismegiste\Genetic\Game\Vda;

use Trismegiste\Genetic\Game\Fighter;
use Trismegiste\Genetic\Game\MutableFighter;

/**
 * A VDA Character
 */
class Character extends MutableFighter {

    protected $health;

    public function getFitness() {
        return $this->victory;
    }

    public function isDead() {
        
    }

    public function newGeneration() {
        $this->victory = 0;
    }

    public function receiveAttack(Fighter $pc) {
        
    }

    public function restart() {
        
    }

}
