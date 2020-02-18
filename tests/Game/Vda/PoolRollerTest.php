<?php

namespace test\Vda;

class PoolRollerTest extends \PHPUnit\Framework\TestCase {

    public function testZeroSuccess() {
        $this->assertLessThanOrEqual(0, \Trismegiste\Genetic\Game\Vda\PoolRoller::roll(10, 11));
    }

    public function testFullSuccess() {
        $this->assertLessThanOrEqual(5, \Trismegiste\Genetic\Game\Vda\PoolRoller::roll(5, 1));
    }

    public function testAverage() {
        $sum = 0;
        for ($k = 0; $k < 100; $k++) {
            $sum += \Trismegiste\Genetic\Game\Vda\PoolRoller::roll(1, 10);
        }
        $this->assertLessThan(0.1, abs($sum / 1000));
    }

}
