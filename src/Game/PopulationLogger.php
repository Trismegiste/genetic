<?php

namespace Trismegiste\Genetic\Game;

/**
 * To build a report ih the population
 */
interface PopulationLogger {

    /**
     * Log a population
     * @param array $pop un tableau de MutableFighter classés par Victory décroissant
     */
    public function log(array& $pop);

    public function endLog();
}
