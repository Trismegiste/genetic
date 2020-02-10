<?php

namespace test\SaWo;

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\SaWo\Character;

class CharacterTest extends TestCase {

    public function factory() {
        return [
            [4, 4],
            [8, 6],
            [12, 8]
        ];
    }

    /** @dataProvider factory */
    public function testParry($face, $diff) {
        $sut = new Character(['fighting' => $face]);
        $this->assertEquals($diff, $sut->getParry());
    }

    /** @dataProvider factory */
    public function testToughness($face, $diff) {
        $sut = new Character(['vigor' => $face]);
        $this->assertEquals($diff, $sut->getToughness());
    }

    public function testFailedAttack() {
        $sut = new Character(['fighting' => 8]);
        $attacker = $this->getMockBuilder(Character::class)->getMock();
        $attacker->expects($this->once())
                ->method('getAttack')
                ->willReturn(5);
        $attacker->expects($this->never())
                ->method('getDamage');

        $sut->receiveAttack($attacker);
    }

    public function factoryFight() {
        $sut = new Character(['fighting' => 8]);
        return [[$sut]];
    }

    /** @dataProvider factoryFight */
    public function testSuccessAttackNoDamage($sut) {
        $attacker = $this->getMockBuilder(Character::class)->getMock();
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
        $attacker = $this->getMockBuilder(Character::class)->getMock();
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
        $attacker = $this->getMockBuilder(Character::class)->getMock();
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
        $attacker = $this->getMockBuilder(Character::class)->getMock();
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

}
