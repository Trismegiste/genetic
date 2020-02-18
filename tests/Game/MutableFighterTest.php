<?php

class MutableFighterTest extends \PHPUnit\Framework\TestCase {

    /** @expectedException \InvalidArgumentException */
    public function testBadParameter() {
        $this->getMockBuilder(\Trismegiste\Genetic\Game\MutableFighter::class)
                ->setConstructorArgs([['yolo' => new \stdClass]])
                ->getMock();
    }

}
