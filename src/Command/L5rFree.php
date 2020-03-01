<?php

namespace Trismegiste\Genetic\Command;

use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\L5r\CharacterFactory;
use Trismegiste\Genetic\Game\L5r\Ecosystem;
use Trismegiste\Genetic\Game\MutableFighterFactory;
use Trismegiste\Genetic\Game\PopulationLogger;

/**
 * Free evolution
 */
class L5rFree extends GameFree {

    protected static $defaultName = 'l5r:free';

    protected function configure() {
        parent::configure();
        $this->setDescription("Compute free evolution for L5R");
    }

    protected function buildFactory(): MutableFighterFactory {
        return new CharacterFactory();
    }

    protected function buildWorld(int $n, MutableFighterFactory $fac, PopulationLogger $log): DarwinWorld {
        return new Ecosystem($n, $fac, $log);
    }

}
