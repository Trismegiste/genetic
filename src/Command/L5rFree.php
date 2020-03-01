<?php

namespace Trismegiste\Genetic\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\L5r\CharacterFactory;
use Trismegiste\Genetic\Game\L5r\Ecosystem;

/**
 * Free evolution
 */
class L5rFree extends GameFree {

    protected static $defaultName = 'l5r:free';

    protected function configure() {
        parent::configure();
        $this->setDescription("Compute free evolution for L5R");
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $popSize = $input->getArgument('popSize');
        $maxGeneration = $input->getArgument('maxIter');
        $round = $input->getOption('round');
        $extinctRatio = $input->getOption('extinct') / 100.0;
        $logger = $this->buildLogger($input, $output);
        $univers = new Ecosystem($popSize, new CharacterFactory(), $logger);

        $output->writeln("Free evolution");

        for ($generation = 0; $generation < $maxGeneration; $generation++) {
            $output->writeln("======== Generation $generation ========");
            $univers->evolve($round, $extinctRatio);
        }

        $logger->endLog();
    }

}
