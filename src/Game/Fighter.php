<?php

namespace Trismegiste\Genetic\Game;

/**
 * A fighter
 */
interface Fighter {

    public function receiveAttack(Fighter $att);

    public function addWounds($val);

    public function getAttack();

    public function getDamage();
}
