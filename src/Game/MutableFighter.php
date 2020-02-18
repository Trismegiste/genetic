<?php

namespace Trismegiste\Genetic\Game;

/**
 * An abstract fighter with mutable capabilities
 */
abstract class MutableFighter implements Mutable, Fighter {

    protected $genome;
    protected $victory = 0;

    public function __construct(array $param) {
        foreach ($param as $key => $gene) {
            if (!($gene instanceof Property)) {
                throw new \InvalidArgumentException("$key is not a Property");
            }
        }
        $this->genome = $param;
    }

    public function __clone() {
        $tmp = [];
        foreach ($this->genome as $key => $gene) {
            $tmp[$key] = clone $gene;
        }
        $this->genome = $tmp;
    }

    public function mutate() {
        $search = mt_rand(0, count($this->genome) - 1);
        $idx = 0;
        foreach ($this->genome as $gene) {
            if ($idx === $search) {
                $gene->mutate();
                break;
            }
            $idx++;
        }
    }

    public function __toString() {
        $compil = '';
        foreach ($this->genome as $key => $gene) {
            $compil .= $key . ':' . $gene . ' ';
        }

        return $compil;
    }

    public function getCost() {
        $cost = 0;
        foreach ($this->genome as $gene) {
            $cost += $gene->getCost();
        }

        return $cost;
    }

    public function getVictory() {
        return $this->victory;
    }

    public function incVictory() {
        $this->victory++;
    }

}
