<?php

namespace Trismegiste\Genetic\Game\SaWo;

use RuntimeException;
use Trismegiste\Genetic\Game\Fighter;
use Trismegiste\Genetic\Game\MutableFighter;

/**
 * A SaWo character
 */
class Character extends MutableFighter
{

    protected $wound = 0;
    protected $fighting = 6;
    protected $victory = 0;
    protected $usedBenny = 0;
    protected $benniesCount = 3;
    protected $genome;
    protected $shaken = false;
    protected $weapon = 8;

    public function getFitness()
    {
        return $this->victory;
    }

    public function newGeneration()
    {
        $this->victory = 0;
    }

    public function isDead(): bool
    {
        return $this->wound > 3;
    }

    public function receiveAttack(Fighter $pc): void
    {
        $parry = $this->getParry();
        $attacks = $pc->getAttack();

        foreach ($attacks as $attack) {
            if ($attack >= $parry) {
                $damage = $pc->getDamage();
                // Raise ?
                if (($attack - $parry) >= 4) {
                    $damage += DiceRoller::rollExplodingDie(6);
                }
                $this->receiveDamage($damage);
            }
        }
    }

    protected function receiveDamage($damage)
    {
        // compare damage and toughness
        if ($damage >= $this->getToughness()) {
            $delta = floor(($damage - $this->getToughness()) / 4);
            $this->addWounds($delta);
        }
    }

    protected function addWounds(int $w)
    {
        if ($w === 0) {
            // new shaken condition :
            if ($this->shaken) {
                if (($this->genome['benny']->get() === 'shaken') && $this->hasBenny()) {
                    $this->useBenny();
                } else {
                    $this->wound++;
                }
            } else {
                $this->shaken = true;
            }
        } else {
            if (($this->genome['benny']->get() === 'soak') && $this->hasBenny()) {
                $this->useBenny();
                $soak = floor($this->roll('vigor') / 4);
                $w -= $soak;
            }
            if ($w > 0) {
                $this->wound += $w;
                $this->shaken = true;
            }
        }
    }

    protected function roll(string $trait): int
    {
        return DiceRoller::rollJoker($this->genome[$trait]) + $this->getWoundsPenalty();
    }

    protected function useBenny()
    {
        if (!$this->hasBenny()) {
            throw new RuntimeException("No more benny to use");
        }
        $this->usedBenny++;
    }

    protected function hasBenny(): bool
    {
        return $this->usedBenny < $this->benniesCount;
    }

    public function getDamage()
    {
        $damage = $this->rollDamage();

        if (($damage < 8) && ($this->genome['benny']->get() === 'damage') && $this->hasBenny()) {
            $damage = max($damage, $this->rollDamage());
            $this->useBenny();
        }

        return $damage;
    }

    protected function rollDamage(): int
    {
        $dice = min([$this->genome['strength']->get(), $this->weapon]);

        return DiceRoller::roll($this->genome['strength']) +
                DiceRoller::rollExplodingDie($dice) +
                $this->genome['attack']->getBonus();
    }

    public function restart()
    {
        $this->wound = 0;
        $this->usedBenny = 0;
        $this->shaken = false;
    }

    public function getParry()
    {
        return $this->genome['fighting']->getDifficulty() +
                $this->genome['block']->get() -
                $this->genome['attack']->getBonus();
    }

    public function getToughness(): int
    {
        return $this->genome['vigor']->getDifficulty();
    }

    public function __toString()
    {
        $compil = '';
        foreach ($this->genome as $key => $gene) {
            $compil .= $key . ':' . $gene . ' ';
        }

        return $compil . 'win:' . $this->victory . ' cost:' . $this->getCost();
    }

    protected function tryUnshake(): void
    {
        $unshake = $this->roll('spirit');
        if ($unshake >= 4) {
            $this->shaken = false;
            return;
        }

        if (($this->genome['benny']->get() === 'shaken') && ($this->hasBenny())) {
            $this->shaken = false;
            $this->useBenny();
        }
    }

    public function getAttack(): array
    {
        if ($this->isShaken()) {
            $this->tryUnshake();
        }
        if ($this->isShaken()) {
            return [0];
        }

        $nb = $this->genome['multiattack']->get();
        $rofEdge = $rof = $this->genome['frenzy']->get();
        $roll = [];
        for ($k = 0; $k < $nb; $k++) {
            if ($rof === 0) {
                $roll[] = $this->getOneAttack();
            } else {
                $roll = array_merge($roll, $this->getOneAttackRateOfFire($rofEdge));
                $rof--;
            }
        }

        return $roll;
    }

    protected function getOneAttack(): int
    {
        $roll = $this->rollFighting();
        if (($roll < 4) && ($this->genome['benny']->get() === 'attack') && $this->hasBenny()) {
            $roll = $this->rollFighting();
            $this->useBenny();
        }

        return $roll;
    }

    protected function getOneAttackRateOfFire(int $rateOfFire): array
    {
        $roll = $this->rollFightingRateOfFire($rateOfFire);

        if ((max($roll) < 4) && ($this->genome['benny']->get() === 'attack') && $this->hasBenny()) {
            $roll = $this->rollFightingRateOfFire($rateOfFire);
            $this->useBenny();
        }

        return $roll;
    }

    protected function rollFighting(): int
    {
        return $this->roll('fighting') +
                $this->genome['trademark']->get() +
                $this->genome['attack']->getBonus() +
                $this->genome['multiattack']->getPenalty();
    }

    protected function rollFightingRateOfFire(int $rateOfFire): array
    {
        $pool = DiceRoller::rollJokerRateOfFire($this->genome['fighting'], $rateOfFire);

        array_walk($pool, function (int &$value) {
            $value += $this->getWoundsPenalty() +
                    $this->genome['trademark']->get() +
                    $this->genome['attack']->getBonus() +
                    $this->genome['multiattack']->getPenalty();
        });

        return $pool;
    }

    public function getWoundsPenalty()
    {
        $ignore = $this->genome['nervesteel']->get();
        $effectiveWound = $this->wound > $ignore ? $this->wound - $ignore : 0;

        return -$effectiveWound;
    }

    public function isShaken(): bool
    {
        return $this->shaken;
    }

    public function getInitiative()
    {
        $first = $this->genome['levelhead']->drawCard();
        $init = $this->genome['quick']->retryCard($first);

        return $init;
    }

}
