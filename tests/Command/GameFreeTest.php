<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Trismegiste\Genetic\Command\GameFree;
use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\MutableFighterFactory;
use Trismegiste\Genetic\Game\PopulationLogger;

class GameFreeTest extends TestCase {

    public function testExecute() {
        $application = new Application();
        $application->setAutoExit(false);
        $command = new MockCommand($this);
        $application->add($command);
        $tester = new CommandTester($application->find('mock'));

        $this->assertEquals(0, $tester->execute([
                    'popSize' => 10,
                    'maxIter' => 5,
                    '--round' => 3,
                    '--extinct' => 0.5
        ]));
    }

}

class MockCommand extends GameFree {

    protected $phpunit;

    public function __construct(TestCase $phpunit) {
        $this->phpunit = $phpunit;
        parent::__construct('mock');
    }

    protected function buildFactory(): MutableFighterFactory {
        return $this->phpunit->getMockBuilder(MutableFighterFactory::class)
                        ->getMock();
    }

    protected function buildWorld(int $n, MutableFighterFactory $fac, PopulationLogger $log): DarwinWorld {
        return $this->phpunit->getMockBuilder(DarwinWorld::class)
                        ->getMock();
    }

}
