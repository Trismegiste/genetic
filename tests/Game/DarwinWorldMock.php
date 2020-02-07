<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\DarwinWorld;

/**
 * Mock of DarwinWorld
 */
abstract class DarwinWorldMock extends DarwinWorld {

    public function __construct(TestCase $build) {
        for ($k = 0; $k < 10; $k++) {
            $pc = $build->getMockBuilder(Trismegiste\Genetic\Game\Mutable::class)->getMock();
            $pc->expects($build->once())->method('newGeneration');
            $pc->expects($build->once())->method('mutate');
            $pc->expects($build->atLeastOnce())->method('getFitness');
            $this->population[] = $pc;
        }
    }

}
