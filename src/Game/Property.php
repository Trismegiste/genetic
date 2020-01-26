<?php

namespace Trismegiste\Genetic\Game;

/**
 * a property in a character
 */
interface Property {

    public function get();

    public function mutate();

    public function getCost();
}
