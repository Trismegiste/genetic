<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\L5r\DiceRoller;

class DiceRollerTest extends TestCase {

    public function testRollD10() {
        $this->assertGreaterThan(0, DiceRoller::rollD10());
    }

}
