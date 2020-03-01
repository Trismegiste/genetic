<?php

namespace Trismegiste\Genetic\Command;

use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\MutableFighterFactory;
use Trismegiste\Genetic\Game\PopulationLogger;
use Trismegiste\Genetic\Game\SaWo\CharacterFactory;
use Trismegiste\Genetic\Game\SaWo\FreeEcosystem;

/**
 * Free evolution for SaWo
 */
class SaWoFree extends GameFree {

    protected static $defaultName = 'sawo:free';

    protected function configure() {
        parent::configure();
        $this->setDescription("Compute free evolution for SaWo");
    }

    protected function buildFactory(): MutableFighterFactory {
        return new CharacterFactory();
    }

    protected function buildWorld(int $n, MutableFighterFactory $fac, PopulationLogger $log): DarwinWorld {
        return new FreeEcosystem($n, $fac, $log);
    }

}
