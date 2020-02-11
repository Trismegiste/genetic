<?php

namespace test\SaWo;

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\SaWo\FreeEcosystem;
use Trismegiste\Genetic\Game\SaWo\Factory;

class FreeEcosystemTest extends TestCase {

    public function create() {
        return [
            [new FreeEcosystem(new Factory(10))]
        ];
    }

    /** @dataProvider create */
    public function testGetSize(FreeEcosystem $sut) {
        $this->assertEquals(10, $sut->getSize());
    }

}
