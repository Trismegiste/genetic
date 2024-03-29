<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Util\PlotterXY;

class PlotterXYTest extends TestCase
{

    /** @dataProvider dataSet */
    public function testExtremum($data, $expect)
    {
        $sut = new PlotterXY(50, 50, 'dummy');
        $grafx[0] = $data;
        $boundaries = $sut->getBoundaries($grafx);
        $this->assertEquals($expect, $boundaries);
    }

    public function dataSet()
    {
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

    public function testDrawing()
    {
        $fch = tempnam(__DIR__, 'test');
        $this->assertFileExists($fch);
        $sut = new PlotterXY(500, 500, $fch);
        $data = [];
        for ($k = 0; $k < 10; $k++) {
            $data[$k] = [];
            for ($c = 0; $c < 500; $c += 10) {
                $data[$k][] = ['x' => $c, 'y' => $k * 50];
            }
        }
        $sut->draw($data);
        list($width, $height) = getimagesize($fch);
        $this->assertEquals(500, $width);
        $this->assertEquals(500, $height);
        unlink($fch);
    }

}
