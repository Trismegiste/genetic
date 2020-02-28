<?php

namespace test\SaWo;

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\SaWo\Character;
use Trismegiste\Genetic\Game\SaWo\CharacterFactory;

class CharacterTest extends TestCase {

    protected static $factory;

    public static function setUpBeforeClass() {
        self::$factory = new CharacterFactory();
    }

    public function factory() {
        return [
            [4, 4],
            [8, 6],
            [12, 8]
        ];
    }

    /** @dataProvider factory */
    public function testParry($face, $diff) {
        $sut = self::$factory->create(['fighting' => $face]);
        $this->assertEquals($diff, $sut->getParry());
    }

    /** @dataProvider factory */
    public function testWildParry($face, $diff) {
        $sut = self::$factory->create(['attack' => 'wild', 'fighting' => $face]);
        $this->assertEquals($diff - 2, $sut->getParry());
    }

    /** @dataProvider factory */
    public function testToughness($face, $diff) {
        $sut = self::$factory->create(['vigor' => $face]);
        $this->assertEquals($diff, $sut->getToughness());
    }

    /** @dataProvider factoryFight */
    public function testFailedAttack($sut) {
        $attacker = $this->getMockBuilder(Character::class)
                ->setConstructorArgs([[]])
                ->getMock();
        $attacker->expects($this->once())
                ->method('getAttack')
                ->willReturn(5);
        $attacker->expects($this->never())
                ->method('getDamage');

        $sut->receiveAttack($attacker);
    }

    public function factoryFight() {
        $f = new CharacterFactory();
        $sut = $f->create(['fighting' => 8]);
        return [[$sut]];
    }

    /** @dataProvider factoryFight */
    public function testSuccessAttackNoDamage($sut) {
        $attacker = $this->getMockBuilder(Character::class)
                ->setConstructorArgs([[]])
                ->getMock();
        $attacker->expects($this->once())
                ->method('getAttack')
                ->willReturn(6);
        $attacker->expects($this->once())
                ->method('getDamage')
                ->willReturn(4);

        $sut->receiveAttack($attacker);
        $this->assertFalse($sut->isShaken());
    }

    /** @dataProvider factoryFight */
    public function testSuccessAttackShaken($sut) {
        $attacker = $this->getMockBuilder(Character::class)
                ->setConstructorArgs([[]])
                ->getMock();
        $attacker->expects($this->once())
                ->method('getAttack')
                ->willReturn(6);
        $attacker->expects($this->once())
                ->method('getDamage')
                ->willReturn(5);

        $sut->receiveAttack($attacker);
        $this->assertTrue($sut->isShaken());
        $this->assertEquals(0, $sut->getWoundsPenalty());
    }

    /** @dataProvider factoryFight */
    public function testSuccessAttackWound($sut) {
        $attacker = $this->getMockBuilder(Character::class)
                ->setConstructorArgs([[]])
                ->getMock();
        $attacker->expects($this->once())
                ->method('getAttack')
                ->willReturn(6);
        $attacker->expects($this->once())
                ->method('getDamage')
                ->willReturn(9);

        $sut->receiveAttack($attacker);
        $this->assertTrue($sut->isShaken());
        $this->assertEquals(-1, $sut->getWoundsPenalty());
    }

    /** @dataProvider factoryFight */
    public function testSuccessAttack3Wound($sut) {
        $attacker = $this->getMockBuilder(Character::class)
                ->setConstructorArgs([[]])
                ->getMock();

        $attacker->expects($this->once())
                ->method('getAttack')
                ->willReturn(6);
        $attacker->expects($this->once())
                ->method('getDamage')
                ->willReturn(17);

        $sut->receiveAttack($attacker);
        $this->assertTrue($sut->isShaken());
        $this->assertEquals(-3, $sut->getWoundsPenalty());
    }

    /** @dataProvider factoryFight */
    public function testClonable(Character $sut) {
        $clone = clone $sut;
        $sut->incVictory();
        $this->assertNotEquals($clone, $sut);
    }

    /** @dataProvider factoryFight */
    public function testDeepClonable(Character $sut) {
        $clone = clone $sut;
        $sut->mutate();
        $this->assertNotEquals($clone, $sut);
    }

    /** @dataProvider factoryFight */
    public function testVictory(Character $sut) {
        $sut->incVictory();
        $this->assertEquals(1, $sut->getVictory());
    }

    /** @dataProvider factoryFight */
    public function testNewGeneration(Character $sut) {
        $sut->incVictory();
        $this->assertEquals(1, $sut->getVictory());
        $sut->newGeneration();
        $this->assertEquals(0, $sut->getVictory());
    }

    /** @dataProvider factoryFight */
    public function testRestart(Character $sut) {
        $attacker = $this->getMockBuilder(Character::class)
                ->setConstructorArgs([[]])
                ->getMock();
        $attacker->expects($this->once())
                ->method('getAttack')
                ->willReturn(12);
        $attacker->expects($this->once())
                ->method('getDamage')
                ->willReturn(21);

        $sut->receiveAttack($attacker);
        $this->assertTrue($sut->isDead());
        $this->assertTrue($sut->isShaken());
        $sut->restart();
        $this->assertEquals(0, $sut->getWoundsPenalty());
        $this->assertFalse($sut->isDead());
        $this->assertFalse($sut->isShaken());
    }

    public function testCost() {
        $sut = self::$factory->create();
        $this->assertEquals(9, $sut->getCost());
    }

    /** @dataProvider factoryFight */
    public function testToString($sut) {
        $this->assertStringStartsWith('agility', (string) $sut);
    }

    /** @dataProvider factoryFight */
    public function testDamage(Character $sut) {
        $this->assertGreaterThanOrEqual(2, $sut->getDamage());
    }

    /** @dataProvider factoryFight */
    public function testAttack(Character $sut) {
        $this->assertGreaterThanOrEqual(1, $sut->getAttack());
    }

}
