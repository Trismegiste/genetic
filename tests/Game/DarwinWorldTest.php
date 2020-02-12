<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\Mutable;
use Trismegiste\Genetic\Game\PopulationFactory;
use Trismegiste\Genetic\Game\PopulationLogger;

/**
 * Test for DarwinWorld
 */
class DarwinWorldTest extends TestCase {

    protected function getFactoryMock() {
        $factory = $this->getMockForAbstractClass(PopulationFactory::class);
        $factory->expects($this->once())
                ->method('create')
                ->willReturn([$this->getMockForAbstractClass(Mutable::class)]);

        return $factory;
    }

    public function testPopulation() {
        $factory = $this->getFactoryMock();
        $log = $this->getMockForAbstractClass(PopulationLogger::class);

        $sut = $this->getMockForAbstractClass(DarwinWorld::class, [$factory, $log]);
        $this->assertEquals(1, $sut->getSize());
    }

    public function testEvolve() {
        $factory = $this->getFactoryMock();
        $log = $this->getMockForAbstractClass(PopulationLogger::class);

        $sut = $this->getMockBuilder(DarwinWorld::class)
                ->setConstructorArgs([$factory, $log])
                ->getMockForAbstractClass();
        $sut->evolve(3, 0.5);
    }

}
