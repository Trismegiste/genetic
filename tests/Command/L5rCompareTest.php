<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Trismegiste\Genetic\Command\L5rCompare;

class L5rCompareTest extends TestCase {

    public function testExecute() {
        $application = new Application();
        $application->setAutoExit(false);
        $command = new L5rCompare();
        $application->add($command);
        $tester = new CommandTester($application->find('l5r:compare'));

        $this->assertEquals(0, $tester->execute([
                    'config' => __DIR__ . '/../testcmd.json'
        ]));

        $this->assertMatchesRegularExpression("/Generation 0/", $tester->getDisplay());
    }

}
