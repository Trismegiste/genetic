<?php

namespace test\SaWo\Property;

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\Property;
use Trismegiste\Genetic\Game\SaWo\Property\BennyStrat;

class BennyStratTest extends TestCase {

    public function factory() {
        return [[new BennyStrat('soak')]];
    }

    /** @dataProvider factory */
    public function testGetter(Property $sut) {
        $this->assertEquals('soak', $sut->get());
        $this->assertEquals(0, $sut->getCost());
    }

    /** @dataProvider factory */
    public function testMutation(Property $sut) {
        $old = $sut->get();
        $sut->mutate();
        $this->assertNotEquals($old, $sut->get());
    }

}
