<?php

namespace test\SaWo\Property;

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\SaWo\Property\Attribute;

class AttributeTest extends TestCase {

    public function factory() {
        return [
            [new Attribute(4), 0],
            [new Attribute(8), 4],
            [new Attribute(12), 8],
            [new Attribute(12, 1), 10],
            [new Attribute(12, 2), 12]
        ];
    }

    /** @dataProvider factory  */
    public function testCost($sut, $cost) {
        $this->assertEquals($cost, $sut->getCost());
    }

}
