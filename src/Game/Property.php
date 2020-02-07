<?php

namespace Trismegiste\Genetic\Game;

/**
 * a property in a character : this property is mutable
 */
interface Property {

    /**
     * Gets the value of this gene
     */
    public function get();

    /**
     * Mutates this gene
     */
    public function mutate();

    /**
     * Gets the gene cost
     */
    public function getCost();
}
