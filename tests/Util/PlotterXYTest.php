<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlotterXYTest
 *
 * @author flo
 */
class PlotterXYTest extends \PHPUnit\Framework\TestCase {

    public function testWriteImage() {
        $sut = new Trismegiste\Genetic\Util\PlotterXY(50, 50);
        $fch = tempnam(__DIR__, 'test');
        $this->assertFileExists($fch);
        $sut->writePng($fch);
        list($width, $height, $type, $attr) = getimagesize($fch);
        $this->assertEquals(50, $width);
        $this->assertEquals(50, $height);
        unlink($fch);
    }

}
