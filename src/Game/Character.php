<?php

namespace Trismegiste\Genetic\Game;

/**
 * A character
 */
interface Character {

    public function getName();

    public function isDead();

    public function restart();
}
