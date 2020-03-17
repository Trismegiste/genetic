<?php

namespace Trismegiste\Genetic\Game\SaWo\Property;

use Trismegiste\Genetic\Game\Property;

/**
 * Quick Edge
 */
class QuickEdge implements Property {

    protected $hasEdge;

    public function __construct(bool $v) {
        $this->hasEdge = $v;
    }

    public function get() {
        return $this->hasEdge;
    }

    public function getCost() {
        return $this->hasEdge ? 2 : 0;
    }

    public function mutate() {
        $this->hasEdge = !$this->hasEdge;
    }

    public function retryCard(int $current): int {
        if (!$this->hasEdge) {
            return $current;
        }

        $retry = 1;
        while ($retry < 3) {
            if ($current > 20) {
                return $current;
            }
            $newcard = mt_rand(1, 54);
            if ($newcard > $current) {
                $current = $newcard;
            }
            $retry++;
        }

        return $current;
    }

    public function __toString() {
        return $this->hasEdge ? 'yes' : 'no';
    }

}
