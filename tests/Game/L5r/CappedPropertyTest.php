<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\L5r\Property\CappedProperty;

class CappedPropertyTest extends TestCase {

    /** @expectedException \OutOfBoundsException */
    public function testBounderiesConstructor() {
        $this->getMockForAbstractClass(CappedProperty::class, [555, 1, 10]);
    }

    public function testGetter() {
        $sut = $this->getMockForAbstractClass(CappedProperty::class, [5, 1, 10]);
        $this->assertEquals(5, $sut->get());
    }

    public function testMutation() {
        $sut = $this->getMockForAbstractClass(CappedProperty::class, [5, 1, 10]);
        $sut->mutate();
        $this->assertNotEquals(5, $sut->get());
    }

}
