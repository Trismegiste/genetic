<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\L5r\Factory;
use Trismegiste\Genetic\Game\L5r\FreeEcosystem;
use Trismegiste\Genetic\Game\PopulationLogger;

/**
 * Test for FreeEcosystem
 */
class FreeEcosystemTest extends TestCase {

    public function testGetSize() {
        $log = $this->getMockForAbstractClass(PopulationLogger::class);

        $sut = new FreeEcosystem(new Factory(10), $log);
        $this->assertEquals(10, $sut->getSize());
    }

    public function testEvolve() {
        $log = $this->getMockForAbstractClass(PopulationLogger::class);
        $log->expects($this->atLeastOnce())
                ->method('log');
        $sut = new FreeEcosystem(new Factory(10), $log);
        $sut->evolve(3, 0.05);
    }

}
