<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\L5r\ComparedEcosystem;

/**
 * Test for ComparedEcosystem
 */
class ComparedEcosystemTest extends TestCase {

    /** @dataProvider create */
    public function testGetSize(ComparedEcosystem $sut) {
        $this->assertEquals(10, $sut->getSize());
    }

    /** @dataProvider create */
    public function testEvolve(ComparedEcosystem $sut) {
        $report = $sut->evolve(3, 0.05);
        $this->assertGreaterThanOrEqual(1, count($report));
    }

    public function create() {
        return [[new ComparedEcosystem(10, [], 0.5)]];
    }

}
