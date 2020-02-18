<?php

namespace test\Vda;

class FreeEvolutionTest extends \PHPUnit\Framework\TestCase {

    public function testEvolve() {
        $dummy = $this->getMockBuilder(\Trismegiste\Genetic\Game\MutableFighter::class)
                ->setConstructorArgs([[]])
                ->getMock();
        $dummy->expects($this->any())
                ->method('isDead')
                ->willReturn(true);

        $fac = $this->getMockBuilder(\Trismegiste\Genetic\Game\Vda\CharacterFactory::class)
                ->getMock();
        $fac->expects($this->exactly(5))
                ->method('createRandom')
                ->willReturn($dummy);

        $log = $this->getMockForAbstractClass(\Trismegiste\Genetic\Game\PopulationLogger::class);

        $sut = new \Trismegiste\Genetic\Game\Vda\FreeEvolution(5, $fac, $log);
        $sut->evolve(3, 0.5);
    }

}
