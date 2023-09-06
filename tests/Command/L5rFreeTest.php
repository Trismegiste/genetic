<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Trismegiste\Genetic\Command\L5rFree;

class L5rFreeTest extends TestCase {

    public function testExecute() {
        $application = new Application();
        $application->setAutoExit(false);
        $command = new L5rFree();
        $application->add($command);
        $tester = new CommandTester($application->find('l5r:free'));

        $this->assertEquals(0, $tester->execute([
                    'popSize' => 10,
                    'maxIter' => 5,
                    '--round' => 3,
                    '--extinct' => 0.5
        ]));

        $this->assertMatchesRegularExpression("/Generation 0/", $tester->getDisplay());
    }

}
