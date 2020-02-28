<?php

namespace Trismegiste\Genetic\Game;

/**
 * A factory for a subclass of MutableFighter
 */
interface MutableFighterFactory {

    public function create(array $param = []): MutableFighter;

    public function createRandom(): MutableFighter;

    public function createSpawn(array $partner): MutableFighter;
}
