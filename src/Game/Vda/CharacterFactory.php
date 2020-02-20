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
        'melee' => 3
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
            'melee' => new Property\Ability($default['melee'])
        ];

        return new Character($genome);
    }

    public function createRandom() {
        return $this->create([
                    'strength' => mt_rand(1, 5),
                    'wits' => mt_rand(1, 5),
                    'dexterity' => mt_rand(1, 5),
                    'stamina' => mt_rand(1, 5),
                    'melee' => mt_rand(1, 5)
        ]);
    }

}
