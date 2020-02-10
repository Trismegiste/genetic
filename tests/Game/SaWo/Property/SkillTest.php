<?php

namespace test\SaWo\Property;

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\SaWo\Property\Skill;
use Trismegiste\Genetic\Game\SaWo\Property\Attribute;

class SkillTest extends TestCase {

    public function factory() {
        $attr = new Attribute(8);
        $attrLeg = new Attribute(12, 1);
        return [
            [new Skill($attr, 4), 0],
            [new Skill($attr, 6), 1],
            [new Skill($attr, 8), 2],
            [new Skill($attr, 10), 4],
            [new Skill($attr, 12), 6],
            [new Skill($attr, 12, 1), 8],
            [new Skill($attrLeg, 12, 2), 8]
        ];
    }

    /** @dataProvider factory  */
    public function testCost($sut, $cost) {
        $this->assertEquals($cost, $sut->getCost());
    }

}
