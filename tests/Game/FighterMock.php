<?php

use Trismegiste\Genetic\Game\Fighter;
use Trismegiste\Genetic\Game\Mutable;

class FighterMock implements Mutable, Fighter {

    public function isDead() {
        return true;
    }

    public function __clone() {
        
    }

    public function getCost() {
        
    }

    public function getFitness() {
        
    }

    public function getVictory() {
        
    }

    public function incVictory() {
        
    }

    public function mutate() {
        
    }

    public function newGeneration() {
        
    }

    public function receiveAttack(Fighter $pc) {
        
    }

    public function restart() {
        
    }

}
