<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\L5r\ComparedEcosystem;
use Trismegiste\Genetic\Game\L5r\Factory;
use Trismegiste\Genetic\Game\PopulationLogger;

/**
 * Test for ComparedEcosystem
 */
class ComparedEcosystemTest extends TestCase {

    public function testGetSize() {
        $log = $this->getMockForAbstractClass(PopulationLogger::class);

        $sut = new ComparedEcosystem(10, new \Trismegiste\Genetic\Game\L5r\CharacterFactory(10), $log, [], 0.5);
        $this->assertEquals(10, $sut->getSize());
    }

    public function testEvolve() {
        $log = $this->getMockForAbstractClass(PopulationLogger::class);
        $log->expects($this->atLeastOnce())
                ->method('log');
        $sut = new ComparedEcosystem(10, new \Trismegiste\Genetic\Game\L5r\CharacterFactory(10), $log, [], 0.5);
        $sut->evolve(3, 0.05);
    }

}
