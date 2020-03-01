<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Trismegiste\Genetic\Command\VdaFree;

class VdaFreeTest extends TestCase {

    public function testExecute() {
        $application = new Application();
        $application->setAutoExit(false);
        $command = new VdaFree();
        $application->add($command);
        $tester = new CommandTester($application->find('vda:free'));

        $this->assertEquals(0, $tester->execute([
                    'popSize' => 10,
                    'maxIter' => 5,
                    '--round' => 3,
                    '--extinct' => 0.5
        ]));

        $this->assertRegExp("/Generation 0/", $tester->getDisplay());
    }

}
