<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\MutableFighter;
use Trismegiste\Genetic\Game\MutableFighterFactory;
use Trismegiste\Genetic\Game\PopulationLogger;

/**
 * Test for DarwinWorld
 */
class DarwinWorldTest extends TestCase {

    const popSize = 10;

    protected function getFactoryMock() {
        $factory = $this->getMockBuilder(MutableFighterFactory::class)
                ->enableOriginalConstructor()
                ->getMock();
        $pc = $this->getMockBuilder(MutableFighter::class)
                ->setConstructorArgs([[]])
                ->getMock();
        $pc->expects($this->any())
                ->method('isDead')
                ->willReturn(true);
        $factory->expects($this->exactly(self::popSize))
                ->method('createRandom')
                ->willReturn($pc);

        return $factory;
    }

    public function testPopulation() {
        $factory = $this->getFactoryMock();
        $log = $this->getMockForAbstractClass(PopulationLogger::class);

        $sut = $this->getMockForAbstractClass(DarwinWorld::class, [self::popSize, $factory, $log]);
        $this->assertEquals(self::popSize, $sut->getSize());
    }

    public function testEvolve() {
        $factory = $this->getFactoryMock();
        $log = $this->getMockForAbstractClass(PopulationLogger::class);

        $sut = $this->getMockBuilder(DarwinWorld::class)
                ->setConstructorArgs([self::popSize, $factory, $log])
                ->getMockForAbstractClass();
        $sut->evolve(3, 0.5);
    }

}
