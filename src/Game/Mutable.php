<?php

namespace Trismegiste\Genetic\Game;

/**
 * This object can mutate
 */
interface Mutable {

    public function mutate();

    public function getFitness();

    public function newGeneration();

    public function __clone();
}
