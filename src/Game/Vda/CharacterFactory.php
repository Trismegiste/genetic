<?php

namespace Trismegiste\Genetic\Game\Vda;

use Trismegiste\Genetic\Game\MutableFighter;
use Trismegiste\Genetic\Game\MutableFighterFactory;
use Trismegiste\Genetic\Game\Vda\Property\Ability;
use Trismegiste\Genetic\Game\Vda\Property\Attribute;
use Trismegiste\Genetic\Game\Vda\Property\Discipline;
use Trismegiste\Genetic\Game\Vda\Property\MultipleActionStrat;

/**
 * A factory for Character
 */
class CharacterFactory implements MutableFighterFactory {

    const defaultGenome = [
        'strength' => 2,
        'wits' => 2,
        'dexterity' => 2,
        'stamina' => 2,
        'melee' => 3,
        'action' => 1,
        'fortitude' => 0,
        'potence' => 0,
        'celerity' => 0,
    ];

    public function create(array $param = []): MutableFighter {
        $default = self::defaultGenome;

        // override
        foreach ($param as $key => $val) {
            if (array_key_exists($key, $default)) {
                $default[$key] = $val;
            }
        }

        $genome = [
            'strength' => new Attribute($default['strength']),
            'wits' => new Attribute($default['wits']),
            'dexterity' => new Attribute($default['dexterity']),
            'stamina' => new Attribute($default['stamina']),
            'melee' => new Ability($default['melee']),
            'action' => new MultipleActionStrat($default['action']),
            'fortitude' => new Discipline($default['fortitude']),
            'potence' => new Discipline($default['potence']),
            'celerity' => new Discipline($default['celerity']),
        ];

        return new Character($genome);
    }

    public function createRandom(): MutableFighter {
        $action = mt_rand(0, 4);

        return $this->create([
                    'strength' => mt_rand(1, 5),
                    'wits' => mt_rand(1, 5),
                    'dexterity' => mt_rand(1, 5),
                    'stamina' => mt_rand(1, 5),
                    'melee' => mt_rand(1, 5),
                    'action' => $action + mt_rand(1, 4),
                    'fortitude' => mt_rand(0, 4),
                    'potence' => mt_rand(0, 4),
                    'celerity' => $action
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
