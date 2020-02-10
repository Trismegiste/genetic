<?php

namespace test\SaWo;

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\SaWo\Character;

class CharacterTest extends TestCase {

    public function testParry() {
        $sut = new Character(['fighting' => 8]);
        $this->assertEquals(6, $sut->getParry());
    }

    public function testToughness() {
        $sut = new Character(['vigor' => 8]);
        $this->assertEquals(6, $sut->getToughness());
    }

}
