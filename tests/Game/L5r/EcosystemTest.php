<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\L5r\CharacterFactory;
use Trismegiste\Genetic\Game\L5r\Ecosystem;
use Trismegiste\Genetic\Game\PopulationLogger;

/**
 * Test for FreeEcosystem
 */
class EcosystemTest extends TestCase {

    public function testGetSize() {
        $log = $this->getMockForAbstractClass(PopulationLogger::class);

        $sut = new Ecosystem(10, new CharacterFactory(), $log);
        $this->assertEquals(10, $sut->getSize());
    }

    public function testEvolve() {
        $log = $this->getMockForAbstractClass(PopulationLogger::class);
        $log->expects($this->atLeastOnce())
                ->method('log');
        $sut = new Ecosystem(10, new CharacterFactory(), $log);
        $sut->evolve(3, 0.05);
    }

}
