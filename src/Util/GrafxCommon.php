<?php

namespace Trismegiste\Genetic\Util;

/**
 * Common methods for plotting
 */
trait GrafxCommon {

    public function createImage($width, $height, $red, $green, $blue) {
        $handle = imagecreatetruecolor($width, $height);
        $background = imagecolorallocate($handle, $red, $green, $blue);
        imagefill($handle, 0, 0, $background);
        imagecolordeallocate($handle, $background);

        return $handle;
    }

    /**
     * Gets the min and max for x and y
     * @param array $curves an array of array of ['x' => x, 'y' => y]
     * @return stdClass
     */
    public function getBoundaries(array& $curves): \stdClass {
        $maxHori = $minHori = $curves[0][0]['x'];
        $maxVert = $minVert = $curves[0][0]['y'];
        foreach ($curves as $data) {
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
        }

        return (object) ['infX' => $minHori, 'supX' => $maxHori, 'infY' => $minVert, 'supY' => $maxVert];
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
