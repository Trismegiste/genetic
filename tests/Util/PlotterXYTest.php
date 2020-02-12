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

    /** @dataProvider dataSet */
    public function testExtremum($data, $expect) {
        $sut = new PlotterXY(50, 50);
        $boundaries = $sut->getBoundaries($data);
        $this->assertEquals($expect, $boundaries);
    }

    public function dataSet() {
        return [
            [
                [['x' => 1, 'y' => 1], ['x' => 2, 'y' => 3]],
                (object) ['infX' => 1, 'infY' => 1, 'supX' => 2, 'supY' => 3]
            ],
            [
                [['x' => -1, 'y' => -1], ['x' => -2, 'y' => -3]],
                (object) ['infX' => -2, 'infY' => -3, 'supX' => -1, 'supY' => -1]
            ],
            [
                [['x' => 5, 'y' => -3], ['x' => -2, 'y' => 6]],
                (object) ['infX' => -2, 'infY' => -3, 'supX' => 5, 'supY' => 6]
            ]
        ];
    }

}
