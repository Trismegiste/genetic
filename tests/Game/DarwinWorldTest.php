<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\Mutable;
use Trismegiste\Genetic\Game\PopulationFactory;

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
        $sut = $this->getMockForAbstractClass(DarwinWorld::class, [$factory]);
        $this->assertEquals(1, $sut->getSize());
    }

    public function testEvolve() {
        $factory = $this->getFactoryMock();
        $sut = $this->getMockBuilder(DarwinWorld::class)
                ->setConstructorArgs([$factory])
                ->getMockForAbstractClass();
        $sut->evolve(3, 0.5);
    }

}
