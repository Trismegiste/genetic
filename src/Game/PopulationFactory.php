<?php

namespace Trismegiste\Genetic\Game;

/**
 * Build a population for DarwinWorld
 */
interface PopulationFactory {

    /**
     * Builds the population for the ecosystem
     * @return array An array of objects implementing Mutable and Fighter interfaces
     */
    public function create(): array;
}
