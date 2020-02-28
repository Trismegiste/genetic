<?php

namespace Trismegiste\Genetic\Game\L5r;

use Trismegiste\Genetic\Game\L5r\Property\Ring;
use Trismegiste\Genetic\Game\L5r\Property\RingTrait;
use Trismegiste\Genetic\Game\L5r\Property\Skill;
use Trismegiste\Genetic\Game\L5r\Property\Stance;
use Trismegiste\Genetic\Game\L5r\Property\VoidRing;
use Trismegiste\Genetic\Game\L5r\Property\VoidStrategy;
use Trismegiste\Genetic\Game\MutableFighter;
use Trismegiste\Genetic\Game\MutableFighterFactory;

class CharacterFactory implements MutableFighterFactory {

    const defaultGenome = [
        "agility" => 3,
        "kenjutsu" => 3,
        "void" => 3,
        "reflexe" => 3,
        "earth" => 3,
        "voidStrat" => "attack",
        "stance" => "standard",
        "strength" => 2
    ];

    public function create(array $param = array()): MutableFighter {
        $default = self::defaultGenome;

        // override
        foreach ($param as $key => $val) {
            if (array_key_exists($key, $default)) {
                $default[$key] = $val;
            }
        }

        // initialise
        $genome = [
            'agility' => new RingTrait($default['agility']),
            'kenjutsu' => new Skill($default['kenjutsu']),
            'void' => new VoidRing($default['void']),
            'reflexe' => new RingTrait($default['reflexe']),
            'earth' => new Ring($default['earth']),
            'voidStrat' => new VoidStrategy($default['voidStrat']),
            'stance' => new Stance($default['stance']),
            'strength' => new RingTrait($default['strength'])
        ];

        return new Character($genome);
    }

    public function createRandom(): MutableFighter {
        return $this->create([
                    'voidStrat' => VoidStrategy::getRandomStrat(),
                    'stance' => Stance::getRandomStrat(),
                    'agility' => mt_rand(2, 6),
                    'reflexe' => mt_rand(2, 6),
                    'earth' => mt_rand(2, 6),
                    'kenjutsu' => mt_rand(1, 5),
                    'void' => mt_rand(2, 5),
                    'strength' => mt_rand(2, 6)
        ]);
    }

    public function createSpawn(array $partner): MutableFighter {
        $gb = count($partner);
        $crossed = [];
        $reference = $partner[0]->getGenome();
        foreach ($reference as $key => $gene) {
            $choice = mt_rand(0, $gb - 1);
            $crossed[$key] = clone $partner[$choice]->getGenome()[$key];
        }

        return new Character($crossed);
    }

}
