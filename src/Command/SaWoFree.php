<?php

namespace Trismegiste\Genetic\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\SaWo\CharacterFactory;
use Trismegiste\Genetic\Game\SaWo\FreeEcosystem;

/**
 * Free evolution for SaWo
 */
class SaWoFree extends GameFree {

    protected static $defaultName = 'sawo:free';

    protected function configure() {
        parent::configure();
        $this->setDescription("Compute free evolution for SaWo");
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $popSize = $input->getArgument('popSize');
        $maxGeneration = $input->getArgument('maxIter');
        $round = $input->getOption('round');
        $extinctRatio = $input->getOption('extinct') / 100.0;
        $logger = $this->buildLogger($input, $output);
        $univers = new FreeEcosystem($popSize, new CharacterFactory(), $logger);

        $output->writeln("Free evolution");

        for ($generation = 0; $generation < $maxGeneration; $generation++) {
            $output->writeln("======== Generation $generation ========");
            $univers->evolve($round, $extinctRatio);
        }

        $logger->endLog();
    }

}
