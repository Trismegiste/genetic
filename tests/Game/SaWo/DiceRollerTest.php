<?php

namespace test\SaWo;

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\SaWo\DiceRoller;
use Trismegiste\Genetic\Game\SaWo\Property\SaWoTrait;

class DiceRollerTest extends TestCase {

    const iter = 10000;

    public function getStat() {
        return [
            [6, 4.2],
            [10, 6.1]
        ];
    }

    public function getStatJoker() {
        return [
            [4, 4, 0.62],
            [12, 4, 0.87],
            [4, 6, 0.32],
            [8, 6, 0.48],
            [6, 4, 0.75],
        ];
    }

    /** @dataProvider getStat */
    public function testAverage($face, $average) {
        $sum = 0;
        $dice = new SaWoTrait($face);
        for ($k = 0; $k < self::iter; $k++) {
            $sum += DiceRoller::roll($dice);
        }
        $delta = ($sum / self::iter - $average) / $average;
        $this->assertLessThan(0.1, $delta);
    }

    /** @dataProvider getStatJoker */
    public function testSuccessJoker($face, $fd, $percent) {
        $sum = 0;
        $dice = new SaWoTrait($face);
        for ($k = 0; $k < self::iter; $k++) {
            $sum += (DiceRoller::rollJoker($dice) >= $fd) ? 1 : 0;
        }
        $delta = ($sum / self::iter - $percent) / $percent;
        $this->assertLessThan(0.1, $delta);
    }

}
