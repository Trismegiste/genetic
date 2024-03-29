<?php

namespace Trismegiste\Genetic\Util;

/**
 * Description of AnimateXY
 *
 * @author flo
 */
class AnimateXY extends ImagickPlotter
{

    protected $pattern;

    public function __construct(int $width, int $height, string $patternName)
    {
        parent::__construct($width, $height);
        $this->pattern = $patternName;
    }

    public function draw(array & $data)
    {
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
            $plotColor = imagecolorallocate($handle, (int) $rgb[0], (int) $rgb[1], (int) $rgb[2]);
            // legend
            $step = floor($idx / $curvesCount * 10) / 10;
            if ($lastStep !== $step) {
                imagefttext($handle, (int) ($this->height / 50), 0, (int) ($this->width * 0.92), (int) ($this->height * ($step + 1 / 20)), $plotColor, './bin/akukamu.otf', "Curve $idx");
                $lastStep = $step;
            }
            // plotting
            foreach ($generation as $plot) {
                $x = $this->width * 0.05 + ($plot['x'] - $deltaX) / $scaleX;
                $y = $this->height - ($this->height * 0.05 + ($plot['y'] - $deltaY) / $scaleY);
                imagefilledellipse($handle, (int) $x, (int) $y, 4, 4, $plotColor);
            }
            imagecolordeallocate($handle, $plotColor);
            imagepng($handle, sprintf($this->pattern, $idx));
            imagedestroy($handle);
        }
    }

}
