<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\AggregateLogger;
use Trismegiste\Genetic\Game\PopulationLogger;

class AggregateLoggerTest extends TestCase {

    public function factory() {
        $mock = $this->getMockForAbstractClass(PopulationLogger::class);
        $mock->expects($this->once())
                ->method('log');
        $mock->expects($this->once())
                ->method('endLog');

        return [[new AggregateLogger([$mock])]];
    }

    /** @dataProvider factory */
    public function testLog($sut) {
        $dummy = [];
        $ret = $sut->log($dummy);
        $this->assertNull($ret);
    }

    /** @dataProvider factory */
    public function testEndLog($sut) {
        $ret = $sut->endLog();
        $this->assertNull($ret);
    }

}
