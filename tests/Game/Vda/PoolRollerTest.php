<?php

namespace test\Vda;

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\Vda\PoolRoller;

class PoolRollerTest extends TestCase {

    const manyRoll = 666;

    public function testZeroSuccess() {
        $this->assertLessThanOrEqual(0, PoolRoller::roll(10, 11));
    }

    public function testFullSuccess() {
        $this->assertLessThanOrEqual(5, PoolRoller::roll(5, 1));
    }

    public function testAverage1() {
        $sum = 0;
        for ($k = 0; $k < self::manyRoll; $k++) {
            $sum += PoolRoller::roll(1, 10);
        }
        $this->assertLessThan(0.1, abs($sum / self::manyRoll));
    }

    public function testAverage2() {
        $sum = 0;
        for ($k = 0; $k < self::manyRoll; $k++) {
            $sum += PoolRoller::roll(1, 6);
        }
        $this->assertLessThan(0.1, abs($sum / self::manyRoll) - 4);
    }

}
