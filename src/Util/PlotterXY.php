<?php

namespace Trismegiste\Genetic\Util;

/**
 * A plotter for points with color
 */
class PlotterXY {

    protected $handle;
    protected $width;
    protected $height;

    public function __construct($width, $height) {
        $this->height = $height;
        $this->width = $width;
        $this->handle = imagecreatetruecolor($width, $height);
        $background = imagecolorallocate($this->handle, 0, 0, 0);
        imagefill($this->handle, 0, 0, $background);
        imagecolordeallocate($this->handle, $background);
    }

    public function getBoundaries(array& $data) {
        $maxHori = $minHori = $data[0]['x'];
        $maxVert = $minVert = $data[0]['y'];

        foreach ($data as $plot) {
            $c = $plot['x'];
            $w = $plot['y'];
            if ($c < $minHori) {
                $minHori = $c;
            }
            if ($c > $maxHori) {
                $maxHori = $c;
            }
            if ($w < $minVert) {
                $minVert = $w;
            }
            if ($w > $maxVert) {
                $maxVert = $w;
            }
        }

        return (object) ['infX' => $minHori, 'supX' => $maxHori, 'infY' => $minVert, 'supY' => $maxVert];
    }

    public function draw($data) {
        $minVert = $minHori = 99999;
        $maxVert = $maxHori = 0;
        foreach ($data as $generation) {
            foreach ($generation as $plot) {
                $c = $plot['x'];
                $w = $plot['y'];
                if ($c < $minHori) {
                    $minHori = $c;
                }
                if ($c > $maxHori) {
                    $maxHori = $c;
                }
                if ($w < $minVert) {
                    $minVert = $w;
                }
                if ($w > $maxVert) {
                    $maxVert = $w;
                }
            }
        }

        $deltaX = $minHori;
        $deltaY = $minVert;
        $scaleX = ($maxHori - $minHori) / ($this->width * 0.9);
        $scaleY = ($maxVert - $minVert) / ($this->height * 0.9);

        $curvesCount = count($data);
        $lastStep = -1;
        foreach ($data as $idx => $generation) {
            $hue = 270.0 * $idx / $curvesCount;
            $rgb = $this->hsv2rgb($hue, 1.0, 1.0);
            $plotColor = imagecolorallocate($this->handle, $rgb[0], $rgb[1], $rgb[2]);
            // legend
            $step = floor($idx / $curvesCount * 10) / 10;
            if ($lastStep !== $step) {
                imagefttext($this->handle, 24, 0, $this->width * 0.92, 50 + $this->height * $step, $plotColor, './bin/akukamu.otf', "Curve $idx");
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
    }

    public function writePng($name) {
        imagepng($this->handle, $name);
    }

    public function __destruct() {
        imagedestroy($this->handle);
    }

    /**
     * $c = array($hue, $saturation, $brightness)
     * $hue=[0..360], $saturation=[0..1], $brightness=[0..1]
     */
    protected function hsv2rgb($h, $s, $v) {
        if ($s == 0) {
            return [$v, $v, $v];
        } else {
            $h = ($h %= 360) / 60;
            $i = floor($h);
            $f = $h - $i;
            $q[0] = $q[1] = $v * (1 - $s);
            $q[2] = $v * (1 - $s * (1 - $f));
            $q[3] = $q[4] = $v;
            $q[5] = $v * (1 - $s * $f);

            return [255 * $q[($i + 4) % 6], 255 * $q[($i + 2) % 6], 255 * $q[$i % 6]];
        }
    }

}
