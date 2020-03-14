<?php

namespace Trismegiste\Genetic\Game;

/**
 * this object can fight
 */
interface Fighter {

    public function restart();

    public function incVictory();

    public function isDead(): bool;

    public function receiveAttack(Fighter $pc);

    public function getVictory();
}
