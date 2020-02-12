<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Util\PlotterXY;

class PlotterXYTest extends TestCase {

    public function testWriteImage() {
        $sut = new PlotterXY(50, 50);
        $fch = tempnam(__DIR__, 'test');
        $this->assertFileExists($fch);
        $sut->writePng($fch);
        list($width, $height, $type, $attr) = getimagesize($fch);
        $this->assertEquals(50, $width);
        $this->assertEquals(50, $height);
        unlink($fch);
    }

}
