<?php

namespace Trismegiste\Genetic\Game\Vda;

/**
 * A factory for Character
 */
class CharacterFactory {

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

    public function create(array $param = []) {
        $default = self::defaultGenome;

        // override
        foreach ($param as $key => $val) {
            if (array_key_exists($key, $default)) {
                $default[$key] = $val;
            }
        }

        $genome = [
            'strength' => new Property\Attribute($default['strength']),
            'wits' => new Property\Attribute($default['wits']),
            'dexterity' => new Property\Attribute($default['dexterity']),
            'stamina' => new Property\Attribute($default['stamina']),
            'melee' => new Property\Ability($default['melee']),
            'action' => new Property\MultipleActionStrat($default['action']),
            'fortitude' => new Property\Discipline($default['fortitude']),
            'potence' => new Property\Discipline($default['potence']),
            'celerity' => new Property\Discipline($default['celerity']),
        ];

        return new Character($genome);
    }

    public function createRandom() {
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

    public function createSpawn(array $partner): Character {
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
