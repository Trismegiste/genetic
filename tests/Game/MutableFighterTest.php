<?php

class MutableFighterTest extends \PHPUnit\Framework\TestCase {

    /** @expectedException \InvalidArgumentException */
    public function testBadParameter() {
        $this->getMockBuilder(\Trismegiste\Genetic\Game\MutableFighter::class)
                ->setConstructorArgs([['yolo' => new \stdClass]])
                ->getMock();
    }

    protected function createDummyFighter() {
        $dummy = $this->getMockForAbstractClass(Trismegiste\Genetic\Game\Property::class);

        return $this->getMockBuilder(\Trismegiste\Genetic\Game\MutableFighter::class)
                        ->setConstructorArgs([['yolo' => $dummy]])
                        ->enableOriginalConstructor()
                        ->getMock();
    }

    public function testCost() {
        $sut = $this->createDummyFighter();
        $this->assertEquals(0, $sut->getCost());
    }

}
