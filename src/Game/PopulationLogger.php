<?php

namespace Trismegiste\Genetic\Game;

/**
 * To build a report ih the population
 */
interface PopulationLogger {

    public function log(array& $pop);

    public function endLog();
}
