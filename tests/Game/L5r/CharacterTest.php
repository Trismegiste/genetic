<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\L5r\Character;

class CharacterTest extends TestCase {

    public function testCreation() {
        $o = new Character("yolo");
        $this->assertEquals("yolo", $o->getName());
        $this->assertEquals('attack', $o->getVoidStrat());
    }

    public function testWinningCount() {
        $o = new Character("yolo");
        $this->assertEquals(0, $o->getWinningCount());
        $o->incVictory();
        $this->assertEquals(1, $o->getWinningCount());
    }

    public function testWounds() {
        $o = new Character("yolo");
        $this->assertEquals(0, $o->getWoundPenalty());
        $o->addWounds(15);
        $this->assertEquals(0, $o->getWoundPenalty());
        $o->addWounds(1);
        $this->assertEquals(3, $o->getWoundPenalty());
    }

    public function testDead() {
        $o = new Character("yolo");
        $this->assertFalse($o->isDead());
        $o->addWounds(57);
        $this->assertEquals(1000, $o->getWoundPenalty());
        $this->assertFalse($o->isDead());
        $o->addWounds(1);
        $this->assertTrue($o->isDead());
    }

}
