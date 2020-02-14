<?php

namespace Trismegiste\Genetic\Util;

/**
 * A plotter for points with color
 */
class PlotterXY extends ImagickPlotter {

    protected $handle;
    protected $filename;

    public function __construct(int $width, int $height, string $filename) {
        parent::__construct($width, $height);
        $this->filename = $filename;
        $this->handle = $this->createImage($width, $height, 0, 0, 0);
    }

    public function draw(array& $data) {
        $extrem = $this->getBoundaries($data);

        $deltaX = $extrem->infX;
        $deltaY = $extrem->infY;
        $scaleX = ($extrem->supX - $extrem->infX) / ($this->width * 0.9);
        $scaleY = ($extrem->supY - $extrem->infY) / ($this->height * 0.9);

        $curvesCount = count($data);
        $lastStep = -1;
        foreach ($data as $idx => $generation) {
            $hue = 270.0 * $idx / $curvesCount;
            $rgb = $this->hsv2rgb($hue, 1.0, 1.0);
            $plotColor = imagecolorallocate($this->handle, $rgb[0], $rgb[1], $rgb[2]);
            // legend
            $step = floor($idx / $curvesCount * 10) / 10;
            if ($lastStep !== $step) {
                imagefttext($this->handle, $this->height / 50, 0, $this->width * 0.92, $this->height * ($step + 1 / 20), $plotColor, './bin/akukamu.otf', "Curve $idx");
                $lastStep = $step;
            }
            // plotting
            foreach ($generation as $plot) {
                $x = $this->width * 0.05 + ($plot['x'] - $deltaX) / $scaleX;
                $y = $this->height - ($this->height * 0.05 + ($plot['y'] - $deltaY) / $scaleY);
                imagefilledellipse($this->handle, $x, $y, 4, 4, $plotColor);
            }
            imagecolordeallocate($this->handle, $plotColor);
        }
        imagepng($this->handle, $this->filename);
    }

    public function __destruct() {
        imagedestroy($this->handle);
    }

}
