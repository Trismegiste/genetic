<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Trismegiste\Genetic\Command\GameFree;
use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\Fighter;
use Trismegiste\Genetic\Game\MutableFighter;
use Trismegiste\Genetic\Game\MutableFighterFactory;
use Trismegiste\Genetic\Game\PopulationLogger;

class GameFreeTest extends TestCase {

    public function dataConfig() {
        return [
            'withAnimate' => [true],
            'oneImage' => [false]
        ];
    }

    /** @dataProvider dataConfig */
    public function testExecute($animate) {
        $application = new Application();
        $application->setAutoExit(false);
        $command = new MockCommand($this);
        $application->add($command);
        $tester = new CommandTester($application->find('mock'));

        $this->assertEquals(0, $tester->execute([
                    'popSize' => 10,
                    'maxIter' => 5,
                    '--round' => 3,
                    '--extinct' => 0.5,
                    '--plot' => 'tmp',
                    '--animate' => $animate,
                    '--stat' => true,
                    '--dump' => true
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
        $fighter = $this->phpunit
                ->getMockBuilder(MutableFighter::class)
                ->enableOriginalConstructor()
                ->setConstructorArgs([[]])
                ->getMock();
        $fighter->expects($this->phpunit->any())
                ->method('isDead')
                ->willReturn(true);
        $fighter->expects($this->phpunit->any())
                ->method('getFitness')
                ->willReturnCallback(function() {
                    return random_int(1, 1000);
                });
        $fighter->expects($this->phpunit->any())
                ->method('getCost')
                ->willReturnCallback(function() {
                    return random_int(1, 1000);
                });
        $fighter->expects($this->phpunit->any())
                ->method('getVictory')
                ->willReturnCallback(function() {
                    return random_int(1, 1000);
                });

        $fac = $this->phpunit
                ->getMockBuilder(MutableFighterFactory::class)
                ->getMock();
        $fac->expects($this->phpunit->exactly(10))
                ->method('createRandom')
                ->willReturn($fighter);

        return $fac;
    }

    protected function buildWorld(int $n, MutableFighterFactory $fac, PopulationLogger $log): DarwinWorld {
        return new MockWorld($n, $fac, $log);
    }

}

class MockWorld extends DarwinWorld {

    protected function getInitiativeTurn(Fighter $pc1, Fighter $pc2): array {
        return [$pc1, $pc2];
    }

    protected function selectPopulation(float $extinctRatio) {
        
    }

}
