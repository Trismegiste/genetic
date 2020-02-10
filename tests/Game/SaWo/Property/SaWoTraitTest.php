<?php

namespace test\SaWo\Property;

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\SaWo\Property\SaWoTrait;

class SaWoTraitTest extends TestCase {

    protected function buildMock($d) {
        return $this->getMockForAbstractClass(SaWoTrait::class, [$d]);
    }

    public function factory() {
        return [
            [6, $this->buildMock(6)],
            [12, $this->buildMock(12)],
            [4, $this->buildMock(4)]
        ];
    }

    /** @dataProvider factory */
    public function testRoll($expect, SaWoTrait $sut) {
        $this->assertEquals($expect, $sut->get());
    }

    /** @expectedException \DomainException */
    public function testInvalidDice5() {
        $this->buildMock(5);
    }

    /** @expectedException \DomainException */
    public function testInvalidDice3() {
        $this->buildMock(3);
    }

    /** @expectedException \DomainException */
    public function testInvalidDice13() {
        $this->buildMock(13);
    }

    public function testMutation4() {
        $sut = $this->buildMock(4);
        $sut->mutate();
        $this->assertEquals(6, $sut->get());
    }

    public function testMutation8() {
        $sut = $this->buildMock(8);
        $sut->mutate();
        $this->assertNotEquals(8, $sut->get());
        $this->assertTrue(in_array($sut->get(), [6, 10]));
    }

    public function testMutation12() {
        $sut = $this->buildMock(12);
        $sut->mutate();
        $this->assertEquals(10, $sut->get());
    }

}
