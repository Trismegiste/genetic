<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\Mutable;
use Trismegiste\Genetic\Game\TextLogger;

class TextLoggerTest extends TestCase {

    public function testWriteConsole() {
        $n = 5;
        $out = $this->getMockForAbstractClass(OutputInterface::class);
        $out->expects($this->exactly($n))
                ->method('writeln');
        $pc = $this->getMockBuilder(Mutable::class)
                ->setConstructorArgs([[]])
                ->getMock();

        $sut = new TextLogger($out, 0.5); // print half population
        $pop = array_fill(0, 2 * $n, $pc);
        $sut->log($pop);
    }

}
