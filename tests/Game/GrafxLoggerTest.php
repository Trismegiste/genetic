<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\GrafxLogger;
use Trismegiste\Genetic\Game\Mutable;
use Trismegiste\Genetic\Util\ImagePlotter;

class GrafxLoggerTest extends TestCase {

    public function testLog() {
        $out = $this->getMockForAbstractClass(OutputInterface::class);
        $out->expects($this->exactly(5))
                ->method('writeln');

        $plot = $this->getMockForAbstractClass(ImagePlotter::class);
        $plot->expects($this->once())
                ->method('draw');

        $sut = new GrafxLogger($out, 0.5, $plot);
        $pop = array_fill(0, 10, $this->getMockForAbstractClass(Mutable::class));
        $sut->log($pop);
        $sut->endLog();
    }

}
