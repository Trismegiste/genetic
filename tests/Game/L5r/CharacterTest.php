<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\L5r\Character;

class CharacterTest extends TestCase {

    /**
     * @dataProvider getFighter
     */
    public function testCreation($o) {
        $this->assertEquals("yolo", $o->getName());
        $this->assertEquals('attack', $o->getVoidStrat());
    }

    /**
     * @dataProvider getFighter
     */
    public function testWinningCount($o) {
        $this->assertEquals(0, $o->getWinningCount());
        $o->incVictory();
        $this->assertEquals(1, $o->getWinningCount());
    }

    /**
     * @dataProvider getFighter
     */
    public function testWounds($o) {
        $this->assertEquals(0, $o->getWoundPenalty());
        $o->addWounds(15);
        $this->assertEquals(0, $o->getWoundPenalty());
        $o->addWounds(1);
        $this->assertEquals(3, $o->getWoundPenalty());
    }

    /**
     * @dataProvider getFighter
     */
    public function testDead($o) {
        $this->assertFalse($o->isDead());
        $o->addWounds(57);
        $this->assertEquals(1000, $o->getWoundPenalty());
        $this->assertFalse($o->isDead());
        $o->addWounds(1);
        $this->assertTrue($o->isDead());
    }

    /**
     * @dataProvider getFighter
     */
    public function testFailedAttack($o) {
        $attacker = $this->createMock(Character::class);
        $attacker->expects($this->once())
                ->method('getAttack')
                ->willReturn(1);
        $attacker->expects($this->never())
                ->method('getDamage');
        $o->receiveAttack($attacker);
    }

    /**
     * @dataProvider getFighter
     */
    public function testSuccedAttack($o) {
        $attacker = $this->createMock(Character::class);
        $attacker->expects($this->once())
                ->method('getAttack')
                ->willReturn(20);
        $attacker->expects($this->once())
                ->method('getDamage');
        $o->receiveAttack($attacker);
    }

    public function testFailedAttackWithArmorStrat() {
        $o = new Character("yolo", ['voidStrat' => 'armor']);
        $this->assertEquals('armor', $o->getVoidStrat());

        $attacker = $this->createMock(Character::class);
        $attacker->expects($this->once())
                ->method('getAttack')
                ->willReturn(20);

        $attacker->expects($this->never())
                ->method('getDamage');

        $o->receiveAttack($attacker);
    }

    public function testSuccedAttackWithArmorStrat() {
        $o = new Character("yolo", ['voidStrat' => 'armor']);
        $this->assertEquals('armor', $o->getVoidStrat());

        $attacker = $this->createMock(Character::class);
        $attacker->expects($this->once())
                ->method('getAttack')
                ->willReturn(30);

        $attacker->expects($this->once())
                ->method('getDamage');

        $o->receiveAttack($attacker);
    }

    /**
     * @dataProvider getFighter
     */
    public function testAttackWounds(Character $o) {
        $this->assertFalse($o->isDead());

        $attacker = $this->createMock(Character::class);
        $attacker->expects($this->once())
                ->method('getAttack')
                ->willReturn(20);
        $attacker->expects($this->once())
                ->method('getDamage')
                ->willReturn(58);

        $o->receiveAttack($attacker);
        $this->assertTrue($o->isDead());
    }

    public function testAttackWoundsWithSoak() {
        $o = new Character('yolo', ['voidStrat' => 'soak']);
        $this->assertFalse($o->isDead());

        $attacker = $this->createMock(Character::class);
        $attacker->expects($this->once())
                ->method('getAttack')
                ->willReturn(20);
        $attacker->expects($this->once())
                ->method('getDamage')
                ->willReturn(58);

        $o->receiveAttack($attacker);
        $this->assertFalse($o->isDead());
    }

    public function testAttackWoundsWithSoakButDeadAnyway() {
        $o = new Character('yolo', ['voidStrat' => 'soak']);
        $this->assertFalse($o->isDead());

        $attacker = $this->createMock(Character::class);
        $attacker->expects($this->once())
                ->method('getAttack')
                ->willReturn(20);
        $attacker->expects($this->once())
                ->method('getDamage')
                ->willReturn(68);

        $o->receiveAttack($attacker);
        $this->assertTrue($o->isDead());
    }

    /**
     * @dataProvider getFighter
     */
    public function testVoidPoint(Character $o) {
        $this->assertTrue($o->hasVoidPoint());
        $o->useVoidPoint();
        $this->assertTrue($o->hasVoidPoint());
        $o->useVoidPoint();
        $this->assertTrue($o->hasVoidPoint());
        $o->useVoidPoint();
        $this->assertFalse($o->hasVoidPoint());
    }

    // providers
    public function getFighter() {
        return [[new Character("yolo")]];
    }

    /**
     * @dataProvider getFighter
     */
    public function testRestart(Character $o) {
        $o->useVoidPoint();
        $o->useVoidPoint();
        $o->useVoidPoint();
        $o->addWounds(58);
        $this->assertTrue($o->isDead());
        $this->assertFalse($o->hasVoidPoint());
        $o->restart();
        $this->assertFalse($o->isDead());
        $this->assertTrue($o->hasVoidPoint());
    }

    /**
     * @dataProvider getFighter
     */
    public function testStringable(Character $o) {
        $this->assertStringStartsWith("yolo", (string) $o);
    }

    /**
     * @dataProvider getFighter
     */
    public function testClonable(Character $o) {
        $c = clone $o;
        $this->assertEquals($c, $o);
        $o->incVictory();
        $this->assertNotEquals($c, $o);
    }

    /**
     * @dataProvider getFighter
     */
    public function testMutable(Character $o) {
        for ($k = 0; $k < 10; $k++) {
            $old = (string) $o;
            $o->mutate();
            $this->assertNotEquals($old, json_encode($o));
        }
    }

}
