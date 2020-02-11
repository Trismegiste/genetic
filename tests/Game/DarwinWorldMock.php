<?php

use PHPUnit\Framework\TestCase;
use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\Fighter;

/**
 * Mock of DarwinWorld
 */
abstract class DarwinWorldMock extends DarwinWorld {

    protected $build;

    public function __construct(TestCase $build, $size) {
        $this->build = $build;
        parent::__construct($size);
    }

    protected function createPopulation(int $popSize) {
        $pop = [];
        for ($k = 0; $k < $popSize; $k++) {
            $pc = $this->build->getMockBuilder(FighterMock::class)->getMock();
            $pc->expects($this->build->once())->method('newGeneration');
            $pc->expects($this->build->once())->method('mutate');
            $pc->expects($this->build->atLeastOnce())->method('getFitness');
            $pop[] = $pc;
        }

        return $pop;
    }

    protected function getInitiativeTurn(Fighter $pc1, Fighter $pc2) {
        return [$pc1, $pc2];
    }

}
