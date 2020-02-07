<?php

namespace Trismegiste\Genetic\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\L5r\Ecosystem;

/**
 * Free evolution
 */
class L5rFree extends Command {

    // the name of the command
    protected static $defaultName = 'evolve:free';
    protected $round;
    protected $extinctRatio;
    protected $univers;
    protected $maxGeneration;

    protected function configure() {
        $this->setDescription("Compute free evolution")
                ->addArgument('popSize', InputArgument::REQUIRED, "Population size")
                ->addArgument('maxIter', InputArgument::REQUIRED, "Max iteration")
                ->addOption('round', NULL, InputArgument::OPTIONAL, 'How many round between 2 PC', 5)
                ->addOption('extinct', NULL, InputArgument::OPTIONAL, 'Percentage of how many population are extinct between generation', 10);
    }

    public function initialize(InputInterface $input, OutputInterface $output) {
        $popSize = $input->getArgument('popSize');
        $this->maxGeneration = $input->getArgument('maxIter');
        $this->round = $input->getOption('round');
        $this->extinctRatio = 1 - $input->getOption('extinct') / 100.0;

        $this->univers = new Ecosystem($popSize);
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("Free evolution");

        for ($generation = 0; $generation < $this->maxGeneration; $generation++) {
            $output->writeln("======== Generation $generation ========");
            $report = $this->univers->evolve($this->round, $this->extinctRatio);
            $output->writeln($report);
        }
    }

}
