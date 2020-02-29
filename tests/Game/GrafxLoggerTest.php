<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\GrafxLogger;
use Trismegiste\Genetic\Game\Mutable;
use Trismegiste\Genetic\Util\ImagePlotter;

class GrafxLoggerTest extends TestCase {

    public function testLog() {
        $plot = $this->getMockForAbstractClass(ImagePlotter::class);
        $plot->expects($this->once())
                ->method('draw');

        $sut = new GrafxLogger($plot);
        $pop = array_fill(0, 10, $this->getMockForAbstractClass(Mutable::class));
        $sut->log($pop);
        $sut->endLog();
    }

}
