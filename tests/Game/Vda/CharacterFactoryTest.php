<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\Vda\Character;
use Trismegiste\Genetic\Game\Vda\CharacterFactory;

class CharacterFactoryTest extends TestCase {

    public function testCreate() {
        $sut = new CharacterFactory();
        $obj = $sut->create();
        $this->assertInstanceOf(Character::class, $obj);
    }

    public function testCreateRandom() {
        $sut = new CharacterFactory();
        $obj = $sut->createRandom();
        $this->assertInstanceOf(Character::class, $obj);
    }

}
