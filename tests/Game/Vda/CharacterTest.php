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

    public function getDefaultCharacter() {
        $f = new CharacterFactory();
        return [[$f->create()]];
    }

    /** @dataProvider factory */
    public function testCost(Character $sut, $cost) {
        $this->assertEquals($cost, $sut->getCost());
    }

    /** @dataProvider getDefaultCharacter */
    public function testInitiative($sut) {
        $n = 1000;
        $sum = 0;
        for ($k = 0; $k < $n; $k++) {
            $sum += $sut->rollInitiative();
        }
        $sum /= $n;
        $this->assertLessThan(0.1, ($sum - 9.5) / $sum);  // 5.5+2+2
    }

    /** @dataProvider getDefaultCharacter */
    public function testAttack($sut) {
        $n = 1000;
        $sum = 0;
        for ($k = 0; $k < $n; $k++) {
            $sum += $sut->getAttack();
        }
        $sum /= $n;
        $this->assertLessThan(0.1, ($sum - 2.0) / $sum);  // 5.5+2+2
    }

}
