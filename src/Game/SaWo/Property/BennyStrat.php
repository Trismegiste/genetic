<?php

namespace Trismegiste\Genetic\Game\SaWo\Property;

use Trismegiste\Genetic\Game\Property;

/**
 * Strategy for Bennies
 */
class BennyStrat implements Property
{

    const choice = ['attack', 'soak', 'shaken', 'damage'];

    protected $strat;

    public function __construct(string $s)
    {
        if (!in_array($s, self::choice, true)) {
            throw new \DomainException($s);
        }
        $this->strat = $s;
    }

    public function get()
    {
        return $this->strat;
    }

    public function getCost()
    {
        return 0;
    }

    public function mutate()
    {
        $old = $this->strat;
        do {
            $this->strat = self::getRandomStrat();
        } while ($this->strat === $old);
    }

    static public function getRandomStrat()
    {
        $pick = array_rand(self::choice);

        return self::choice[$pick];
    }

    public function __toString()
    {
        return $this->strat;
    }

}
