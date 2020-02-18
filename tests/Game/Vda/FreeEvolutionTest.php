<?php

namespace test\Vda;

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\MutableFighter;
use Trismegiste\Genetic\Game\PopulationLogger;
use Trismegiste\Genetic\Game\Vda\CharacterFactory;
use Trismegiste\Genetic\Game\Vda\FreeEvolution;

class FreeEvolutionTest extends TestCase {

    public function testEvolve() {
        $dummy = $this->getMockBuilder(MutableFighter::class)
                ->setConstructorArgs([[]])
                ->getMock();
        $dummy->expects($this->any())
                ->method('isDead')
                ->willReturn(true);

        $fac = $this->getMockBuilder(CharacterFactory::class)
                ->getMock();
        $fac->expects($this->exactly(5))
                ->method('createRandom')
                ->willReturn($dummy);

        $log = $this->getMockForAbstractClass(PopulationLogger::class);

        $sut = new FreeEvolution(5, $fac, $log);
        $sut->evolve(3, 0.5);
    }

}
