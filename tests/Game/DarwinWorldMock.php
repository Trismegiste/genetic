<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\DarwinWorld;

/**
 * Mock of DarwinWorld
 */
abstract class DarwinWorldMock extends DarwinWorld {

    protected $build;

    public function __construct(TestCase $build) {
        $this->build = $build;
        parent::__construct(10);
    }

    protected function createPopulation($popSize) {
        $pop = [];
        for ($k = 0; $k < $popSize; $k++) {
            $pc = $this->build->getMockBuilder(Trismegiste\Genetic\Game\Mutable::class)->getMock();
            $pc->expects($this->build->once())->method('newGeneration');
            $pc->expects($this->build->once())->method('mutate');
            $pc->expects($this->build->atLeastOnce())->method('getFitness');
            $pop[] = $pc;
        }

        return $pop;
    }

}
