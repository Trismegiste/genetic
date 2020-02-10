<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\SaWo\Property\SaWoTrait;

class SaWoTraitTest extends TestCase {

    protected function buildMock($d, $b = 0) {
        return $this->getMockForAbstractClass(SaWoTrait::class, [$d, $b]);
    }

    public function factory() {
        return [
            [6, 0, $this->buildMock(6)],
            [12, 0, $this->buildMock(12)],
            [12, 2, $this->buildMock(12, 2)]
        ];
    }

    /** @dataProvider factory */
    public function testRoll($expect, $bonus, SaWoTrait $sut) {
        $this->assertEquals([$expect, $bonus], $sut->get());
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

    /** @expectedException \OutOfBoundsException */
    public function testInvalidBonus() {
        $this->buildMock(10, 1);
    }

    /** @expectedException \OutOfBoundsException */
    public function testNegativeBonus() {
        $this->buildMock(12, -1);
    }

    public function testMutation4() {
        $sut = $this->buildMock(4);
        $sut->mutate();
        $this->assertEquals([6, 0], $sut->get());
    }

    public function testMutation8() {
        $sut = $this->buildMock(8);
        $sut->mutate();
        $this->assertNotEquals([8, 0], $sut->get());
        $this->assertTrue(in_array($sut->get()[0], [6, 10]));
    }

    public function testMutation12() {
        for ($k = 0; $k < 5; $k++) {
            $sut = $this->buildMock(12);
            $sut->mutate();
            $this->assertNotEquals([12, 0], $sut->get());
            $this->assertTrue(in_array($sut->get(), [[10, 0], [12, 1]]));
        }
    }

    public function testMutation12Plus() {
        for ($k = 0; $k < 5; $k++) {
            $sut = $this->buildMock(12, 2);
            $sut->mutate();
            $this->assertNotEquals([12, 2], $sut->get());
            $this->assertTrue(in_array($sut->get(), [[12, 1], [12, 3]]));
        }
    }

}
