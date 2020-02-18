<?php

namespace Trismegiste\Genetic\Game\Vda;

/**
 * A factory for Character
 */
class CharacterFactory {

    const defaultGenome = [
        'strength' => 2,
        'dexterity' => 2,
        'vigor' => 2,
        'melee' => 2
    ];

    public function create(array $param) {
        $default = self::defaultGenome;

        // override
        foreach ($param as $key => $val) {
            if (array_key_exists($key, $default)) {
                $default[$key] = $val;
            }
        }

        $genome = [
            'strength' => 2,
            'dexterity' => 2,
            'vigor' => 2,
            'melee' => 2
        ];

        return new Character($genome);
    }

    public function createRandom() {
        return $this->create([
                    'strength' => mt_rand(1, 5),
                    'dexterity' => mt_rand(1, 5),
                    'vigor' => mt_rand(1, 5),
                    'melee' => mt_rand(1, 5)
        ]);
    }

}
