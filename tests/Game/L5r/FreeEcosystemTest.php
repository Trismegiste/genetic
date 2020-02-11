<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\L5r\Factory;
use Trismegiste\Genetic\Game\L5r\FreeEcosystem;

/**
 * Test for FreeEcosystem
 */
class FreeEcosystemTest extends TestCase {

    /** @dataProvider create */
    public function testGetSize(FreeEcosystem $sut) {
        $this->assertEquals(10, $sut->getSize());
    }

    /** @dataProvider create */
    public function testEvolve(FreeEcosystem $sut) {
        $report = $sut->evolve(3, 0.05);
        $this->assertGreaterThanOrEqual(1, count($report));
    }

    public function create() {
        return [[new FreeEcosystem(new Factory(10))]];
    }

}
