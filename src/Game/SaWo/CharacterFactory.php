<?php

namespace Trismegiste\Genetic\Game\SaWo;

use Trismegiste\Genetic\Game\MutableFighter;
use Trismegiste\Genetic\Game\MutableFighterFactory;
use Trismegiste\Genetic\Game\SaWo\Property\AttackStrat;
use Trismegiste\Genetic\Game\SaWo\Property\Attribute;
use Trismegiste\Genetic\Game\SaWo\Property\BennyStrat;
use Trismegiste\Genetic\Game\SaWo\Property\BlockEdge;
use Trismegiste\Genetic\Game\SaWo\Property\LevelHeadedEdge;
use Trismegiste\Genetic\Game\SaWo\Property\MultiAttackStrat;
use Trismegiste\Genetic\Game\SaWo\Property\NerveOfSteelEdge;
use Trismegiste\Genetic\Game\SaWo\Property\QuickEdge;
use Trismegiste\Genetic\Game\SaWo\Property\Skill;
use Trismegiste\Genetic\Game\SaWo\Property\TradeWeaponEdge;

class CharacterFactory implements MutableFighterFactory
{

    const defaultGenome = [
        'agility' => 6,
        'fighting' => 6,
        'vigor' => 6,
        'strength' => 6,
        'spirit' => 6,
        'benny' => 'attack',
        'block' => 0,
        'trademark' => 0,
        'attack' => 'standard',
        'levelhead' => 0,
        'quick' => false,
        'nervesteel' => 0,
        'frenzy' => 0,
        'multiattack' => 1
    ];

    public function create(array $param = array()): MutableFighter
    {
        $default = self::defaultGenome;

        // override
        foreach ($param as $key => $val) {
            if (array_key_exists($key, $default)) {
                $default[$key] = $val;
            }
        }

        $agility = new Attribute($default['agility']);
        $genome = [
            'agility' => $agility,
            'fighting' => new Skill($agility, $default['fighting']),
            'vigor' => new Attribute($default['vigor']),
            'strength' => new Attribute($default['strength']),
            'spirit' => new Attribute($default['spirit']),
            'benny' => new BennyStrat($default['benny']),
            'block' => new BlockEdge($default['block']),
            'trademark' => new TradeWeaponEdge($default['trademark']),
            'attack' => new AttackStrat($default['attack']),
            'levelhead' => new LevelHeadedEdge($default['levelhead']),
            'quick' => new QuickEdge($default['quick']),
            'nervesteel' => new NerveOfSteelEdge($default['nervesteel']),
            'frenzy' => new Property\FrenzyEdge($default['frenzy']),
            'multiattack' => new MultiAttackStrat($default['multiattack'])
        ];

        return new Character($genome);
    }

    public function createRandom(): MutableFighter
    {
        return $this->create([
                    'strength' => 2 * mt_rand(2, 6),
                    'vigor' => 2 * mt_rand(2, 6),
                    'spirit' => 2 * mt_rand(2, 6),
                    'fighting' => 2 * mt_rand(2, 6),
                    'agility' => 2 * mt_rand(2, 6),
                    'benny' => BennyStrat::getRandomStrat(),
                    'block' => mt_rand(0, 2),
                    'trademark' => mt_rand(0, 2),
                    'attack' => AttackStrat::getRandomStrat(),
                    'levelhead' => mt_rand(0, 2),
                    'quick' => (bool) mt_rand(0, 1),
                    'nervesteel' => mt_rand(0, 2),
                    'frenzy' => mt_rand(0, 2),
                    'multiattack' => mt_rand(1, 3),
        ]);
    }

    public function createSpawn(array $partner): MutableFighter
    {
        $gb = count($partner);
        $crossed = [];
        $reference = array_keys($partner[0]->getGenome());
        foreach ($reference as $key) {
            $choice = mt_rand(0, $gb - 1);
            $crossed[$key] = clone $partner[$choice]->getGenome()[$key];
        }

        return new Character($crossed);
    }

}
