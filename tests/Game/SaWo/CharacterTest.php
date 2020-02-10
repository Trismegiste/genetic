<?php

namespace test\SaWo;

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\SaWo\Character;

class CharacterTest extends TestCase {

    public function factory() {
        return [
            [4, 4],
            [8, 6],
            [12, 8]
        ];
    }

    /** @dataProvider factory */
    public function testParry($face, $diff) {
        $sut = new Character(['fighting' => $face]);
        $this->assertEquals($diff, $sut->getParry());
    }

    /** @dataProvider factory */
    public function testToughness($face, $diff) {
        $sut = new Character(['vigor' => $face]);
        $this->assertEquals($diff, $sut->getToughness());
    }

}
