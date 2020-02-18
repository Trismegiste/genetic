<?php

namespace test\Vda;

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\Vda\Character;
use Trismegiste\Genetic\Game\Vda\CharacterFactory;

class CharacterTest extends TestCase {

    public function factory() {
        $f = new CharacterFactory();
        return [
            [$f->create()]
        ];
    }

    /** @dataProvider factory */
    public function testCost(Character $sut) {
        $this->assertEquals(0, $sut->getCost());
    }

}
