<?php

namespace test\Vda;

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\Vda\Character;
use Trismegiste\Genetic\Game\Vda\CharacterFactory;

class CharacterTest extends TestCase {

    public function factory() {
        $f = new CharacterFactory();
        return [
            [$f->create(), 22], // dexterity = 2
            [$f->create(['dexterity' => 3]), 30]
        ];
    }

    /** @dataProvider factory */
    public function testCost(Character $sut, $cost) {
        $this->assertEquals($cost, $sut->getCost());
    }

}
