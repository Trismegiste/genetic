<?php

namespace test\SaWo;

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\PopulationLogger;
use Trismegiste\Genetic\Game\SaWo\CharacterFactory;
use Trismegiste\Genetic\Game\SaWo\FreeEcosystem;

class FreeEcosystemTest extends TestCase {

    public function create() {
        $log = $this->getMockForAbstractClass(PopulationLogger::class);

        return [
            [new FreeEcosystem(10, new CharacterFactory, $log)]
        ];
    }

    /** @dataProvider create */
    public function testGetSize(FreeEcosystem $sut) {
        $this->assertEquals(10, $sut->getSize());
    }

}
