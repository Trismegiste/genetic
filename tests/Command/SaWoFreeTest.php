<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Tester\CommandTester;

class SaWoFreeTest extends TestCase {

    public function testExecute() {

        $application = new Application();
        $application->setAutoExit(false);
        $command = new Trismegiste\Genetic\Command\SaWoFree();
        $application->add($command);

        $tester = new CommandTester($application->find('sawo:free'));

        $this->assertEquals(0, $tester->execute([
                    'popSize' => 10,
                    'maxIter' => 5,
                    '--round' => 3,
                    '--extinct' => 0.5
        ]));

        $this->assertRegExp("/Generation 0/", $tester->getDisplay());
    }

}
