<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\L5r\DiceRoller;

class DiceRollerTest extends TestCase {

    public function testRollD10() {
        $this->assertGreaterThan(0, DiceRoller::rollD10());
    }

    public function testMultipleRoll() {
        $s = 0;
        for ($k = 0; $k < 1000; $k++) {
            $s += DiceRoller::rollD10();
        }
        $s /= 1000;
        $this->assertGreaterThan(5, $s);
        $this->assertLessThan(7, $s);
    }

    public function testLimitZeroKeep() {
        $this->assertEquals(0, DiceRoller::rollAndKeep(5, 0));
    }

    /**
     * @dataProvider getRolling
     */
    public function testMultipleRollAndKeep($r, $k, $avg) {
        $s = 0;
        for ($idx = 0; $idx < 1000; $idx++) {
            $s += DiceRoller::rollAndKeep($r, $k);
        }
        $delta = ($s / 1000 - $avg) / $avg;
        $this->assertLessThan(0.1, $delta);
    }

    public function getRolling() {
        return[
            [4, 2, 17],
            [6, 3, 27],
            [8, 4, 36],
            [10, 5, 45],
            [20, 10, 100],
            [2, 4, 12]
        ];
    }

}
