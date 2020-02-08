<?php

namespace Trismegiste\Genetic\Game\L5r;

/**
 * FreeEcosystem is a free competition between a random population of L5R PC
 */
class FreeEcosystem extends Ecosystem {

    protected function tournament($round) {
        foreach ($this->population as $idx1 => $pc1) {
            foreach ($this->population as $idx2 => $pc2) {
                if ($idx2 <= $idx1) {
                    continue;
                }
                $this->evaluateBestFighter($round, $pc1, $pc2);
            }
        }
    }

    protected function writeImage() {
        $width = 1000;
        $height = 1000;
        $h = imagecreatetruecolor($width, $height);
        $background = imagecolorallocate($h, 255, 255, 255);
        $plotColor = imagecolorallocate($h, 0, 0, 0);
        imagefill($h, 0, 0, $background);

        $minWin = $minCost = 99999;
        $maxWin = $maxCost = 0;
        foreach ($this->population as $pc) {
            $c = $pc->getCost();
            $w = $pc->getWinningCount();
            if ($c < $minCost) {
                $minCost = $c;
            }
            if ($c > $maxCost) {
                $maxCost = $c;
            }
            if ($w < $minWin) {
                $minWin = $c;
            }
            if ($w > $maxWin) {
                $maxWin = $c;
            }
        }

        foreach ($this->population as $pc) {
            /*     $x = 10 + ($pc->getCost() - $minCost) * ($width - 20) / ($maxCost - $minCost);
              $y = 10 + ($pc->getWinningCount() - $minWin) * ($height - 20) / ($maxWin - $minWin); */
            imagefilledellipse($h, 3 * $pc->getCost(), $height - $height * $pc->getWinningCount() / $this->getSize(), 4, 4, $plotColor);
        }


        imagepng($h, "population" . time() . ".png");
    }

}
