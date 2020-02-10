<?php

namespace Trismegiste\Genetic\Game\SaWo;

/**
 * this object can fight
 */
interface Fighter {

    public function restart();

    public function incVictory();

    public function isDead();

    public function receiveAttack(Fighter $pc);

    public function getVictory();
}
