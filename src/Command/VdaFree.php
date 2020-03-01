<?php

namespace Trismegiste\Genetic\Command;

use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\MutableFighterFactory;
use Trismegiste\Genetic\Game\PopulationLogger;
use Trismegiste\Genetic\Game\Vda\CharacterFactory;
use Trismegiste\Genetic\Game\Vda\FreeEvolution;

/**
 * Free evolution for VDA
 */
class VdaFree extends GameFree {

    protected static $defaultName = 'vda:free';

    protected function configure() {
        parent::configure();
        $this->setDescription("Compute free evolution for VDA");
    }

    protected function buildFactory(): MutableFighterFactory {
        return new CharacterFactory();
    }

    protected function buildWorld(int $n, MutableFighterFactory $fac, PopulationLogger $log): DarwinWorld {
        return new FreeEvolution($n, $fac, $log);
    }

}
