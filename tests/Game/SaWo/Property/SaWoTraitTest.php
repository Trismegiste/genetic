<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\SaWo\Property\SaWoTrait;

class SaWoTraitTest extends TestCase {

    public function factory() {
        return [
            [6, 0, new SaWoTrait(6)],
            [12, 0, new SaWoTrait(12)],
            [12, 2, new SaWoTrait(12, 2)]
        ];
    }

    /** @dataProvider factory */
    public function testRoll($expect, $bonus, SaWoTrait $sut) {
        $this->assertEquals([$expect, $bonus], $sut->get());
    }

    /** @expectedException \DomainException */
    public function testInvalidDice5() {
        new SaWoTrait(5);
    }

    /** @expectedException \DomainException */
    public function testInvalidDice3() {
        new SaWoTrait(3);
    }

    /** @expectedException \DomainException */
    public function testInvalidDice13() {
        new SaWoTrait(13);
    }

    /** @expectedException \OutOfBoundsException */
    public function testInvalidBonus() {
        new SaWoTrait(10, 1);
    }

    /** @expectedException \OutOfBoundsException */
    public function testNegativeBonus() {
        new SaWoTrait(12, -1);
    }

    public function testMutation4() {
        $sut = new SaWoTrait(4);
        $sut->mutate();
        $this->assertEquals([6, 0], $sut->get());
    }

    public function testMutation8() {
        $sut = new SaWoTrait(8);
        $sut->mutate();
        $this->assertNotEquals([8, 0], $sut->get());
        $this->assertTrue(in_array($sut->get()[0], [6, 10]));
    }

    public function testMutation12() {
        for ($k = 0; $k < 5; $k++) {
            $sut = new SaWoTrait(12);
            $sut->mutate();
            $this->assertNotEquals([12, 0], $sut->get());
            $this->assertTrue(in_array($sut->get(), [[10, 0], [12, 1]]));
        }
    }

    public function testMutation12Plus() {
        for ($k = 0; $k < 5; $k++) {
            $sut = new SaWoTrait(12, 2);
            $sut->mutate();
            $this->assertNotEquals([12, 2], $sut->get());
            $this->assertTrue(in_array($sut->get(), [[12, 1], [12, 3]]));
        }
    }

}
