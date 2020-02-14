<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Trismegiste\Genetic\Util;

/**
 * Description of AnimateXY
 *
 * @author flo
 */
class AnimateXY {

    use GrafxCommon;

    protected $width;
    protected $height;

    public function __construct($width, $height) {
        $this->height = $height;
        $this->width = $width;
    }

    public function draw($data) {
        $extrem = $this->getBoundaries($data);

        $deltaX = $extrem->infX;
        $deltaY = $extrem->infY;
        $scaleX = ($extrem->supX - $extrem->infX) / ($this->width * 0.9);
        $scaleY = ($extrem->supY - $extrem->infY) / ($this->height * 0.9);

        $curvesCount = count($data);
        $lastStep = -1;
        foreach ($data as $idx => $generation) {
            $handle = $this->createImage($this->width, $this->height, 0, 0, 0);
            $hue = 270.0 * $idx / $curvesCount;
            $rgb = $this->hsv2rgb($hue, 1.0, 1.0);
            $plotColor = imagecolorallocate($handle, $rgb[0], $rgb[1], $rgb[2]);
            // legend
            $step = floor($idx / $curvesCount * 10) / 10;
            if ($lastStep !== $step) {
                imagefttext($handle, $this->height / 50, 0, $this->width * 0.92, $this->height * ($step + 1 / 20), $plotColor, './bin/akukamu.otf', "Curve $idx");
                $lastStep = $step;
            }
            // plotting
            foreach ($generation as $plot) {
                $x = $this->width * 0.05 + ($plot['x'] - $deltaX) / $scaleX;
                $y = $this->height - ($this->height * 0.05 + ($plot['y'] - $deltaY) / $scaleY);
                imagefilledellipse($handle, $x, $y, 4, 4, $plotColor);
            }
            imagecolordeallocate($handle, $plotColor);
            imagepng($handle, "plot-$idx.png");
            imagedestroy($handle);
        }
    }

}
