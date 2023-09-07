<?php

namespace Trismegiste\Genetic\Game;

/**
 * An abstract fighter with mutable capabilities
 */
abstract class MutableFighter implements Mutable, Fighter
{

    protected $genome;
    protected $victory = 0;

    public function __construct(array $param)
    {
        foreach ($param as $key => $gene) {
            if (!($gene instanceof Property)) {
                throw new \InvalidArgumentException("$key is not a Property");
            }
        }
        $this->genome = $param;
    }

    public function __clone()
    {
        $tmp = [];
        foreach ($this->genome as $key => $gene) {
            $tmp[$key] = clone $gene;
        }
        $this->genome = $tmp;
    }

    public function mutate()
    {
        $pick = array_rand($this->genome);
        $this->genome[$pick]->mutate();
    }

    public function getCost()
    {
        $cost = 0;
        foreach ($this->genome as $gene) {
            $cost += $gene->getCost();
        }

        return $cost;
    }

    public function getVictory()
    {
        return $this->victory;
    }

    public function incVictory()
    {
        $this->victory++;
    }

    public function getGenome(): array
    {
        return $this->genome;
    }

}
