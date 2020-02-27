<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\Vda\Character;
use Trismegiste\Genetic\Game\Vda\CharacterFactory;
use Trismegiste\Genetic\Game\Vda\Property\Discipline;

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

    public function testCreateSpawn() {
        $avg = 2;
        $card = 100;
        $pc1 = new Character([new Discipline($avg - 1)]);
        $pc2 = new Character([new Discipline($avg + 1)]);
        $sut = new CharacterFactory();

        $sum = 0;
        for ($k = 0; $k < $card; $k++) {
            $child = $sut->createSpawn([$pc1, $pc2]);
            $sum += $child->getGenome()[0]->get();
        }
        $this->assertLessThan(0.1, ($sum / $card - $avg) / $avg);
    }

}
