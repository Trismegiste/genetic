<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\TextLogger;

class TextLoggerTest extends TestCase {

    public function testWriteConsole() {
        $out = $this->getMockForAbstractClass(OutputInterface::class);
        $out->expects($this->exactly(5))
                ->method('writeln');
        $sut = new TextLogger($out, 0.5);
        $pop = array_fill(0, 10, 'dummy');
        $sut->log($pop);
    }

}
