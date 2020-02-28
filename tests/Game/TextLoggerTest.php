<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\TextLogger;

class TextLoggerTest extends TestCase {

    public function testWriteConsole() {
        $n = 5;
        $out = $this->getMockForAbstractClass(OutputInterface::class);
        $out->expects($this->exactly($n + 1))
                ->method('writeln');
        $pc = $this->getMockBuilder(Trismegiste\Genetic\Game\Mutable::class)
                ->setConstructorArgs([[]])
                ->getMock();
        $pc->expects($this->exactly(2 * $n))
                ->method('getCost');
        $sut = new TextLogger($out, 0.5);
        $pop = array_fill(0, 2 * $n, $pc);
        $sut->log($pop);
    }

}
