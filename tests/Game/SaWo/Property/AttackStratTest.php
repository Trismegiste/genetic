<?php

namespace test\SaWo\Property;

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\Property;
use Trismegiste\Genetic\Game\SaWo\Property\AttackStrat;

class AttackStratTest extends TestCase {

    public function factory() {
        return [[new AttackStrat('wild')]];
    }

    public function testInvalidStrat() {
        $this->expectException(\DomainException::class);
        new AttackStrat('yolo');
    }

    /** @dataProvider factory */
    public function testGetter(Property $sut) {
        $this->assertEquals('wild', $sut->get());
        $this->assertEquals(2, $sut->getBonus());
        $this->assertEquals(0, $sut->getCost());
    }

    /** @dataProvider factory */
    public function testMutation(Property $sut) {
        $old = $sut->get();
        $sut->mutate();
        $this->assertNotEquals($old, $sut->get());
    }

}
