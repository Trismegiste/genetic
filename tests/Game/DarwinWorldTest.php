<?php

require_once __DIR__ . '/DarwinWorldMock.php';

use PHPUnit\Framework\TestCase;

/**
 * Test for DarwinWorld
 */
class DarwinWorldTest extends TestCase {

    public function create() {
        $sut = $this->getMockForAbstractClass(DarwinWorldMock::class, [$this]);

        return [[$sut]];
    }

    /** @dataProvider create */
    public function testEvolve($sut) {
        $this->assertEquals(10, $sut->getSize());
        $sut->evolve(3, 0.05);
    }

}
