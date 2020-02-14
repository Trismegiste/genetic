<?php

namespace Trismegiste\Genetic\Game;

/**
 * This object can mutate
 */
interface Mutable {

    /**
     * Mutates this object
     */
    public function mutate();

    /**
     * Gets the fitness of this object
     */
    public function getFitness();

    /**
     * Re-initialise ths object for the next generation
     */
    public function newGeneration();

    /**
     * A Mutable object must be clonable
     */
    public function __clone();

    /**
     * Returns the cost of genome
     */
    public function getCost();

    /**
     * Must be printable
     */
    public function __toString();
}
