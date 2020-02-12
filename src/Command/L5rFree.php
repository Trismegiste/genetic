<?php

namespace Trismegiste\Genetic\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\L5r\Factory;
use Trismegiste\Genetic\Game\L5r\FreeEcosystem;
use Trismegiste\Genetic\Game\L5r\GrfxLogger;

/**
 * Free evolution
 */
class L5rFree extends Command {

    // the name of the command
    protected static $defaultName = 'l5r:free';
    protected $round;
    protected $extinctRatio;
    protected $univers;
    protected $maxGeneration;
    protected $plotFile;
    protected $logger;

    protected function configure() {
        $this->setDescription("Compute free evolution")
                ->addArgument('popSize', InputArgument::REQUIRED, "Population size")
                ->addArgument('maxIter', InputArgument::REQUIRED, "Max iteration")
                ->addOption('round', NULL, InputOption::VALUE_REQUIRED, 'How many round between 2 PC', 5)
                ->addOption('extinct', NULL, InputOption::VALUE_REQUIRED, 'Percentage of how many population are extinct between generation', 10)
                ->addOption('plot', NULL, InputOption::VALUE_REQUIRED, 'File name of plotting PNG picture');
    }

    public function initialize(InputInterface $input, OutputInterface $output) {
        $popSize = $input->getArgument('popSize');
        $this->maxGeneration = $input->getArgument('maxIter');
        $this->round = $input->getOption('round');
        $this->extinctRatio = $input->getOption('extinct') / 100.0;
        $this->plotFile = $input->getOption('plot');

        $this->logger = new GrfxLogger($output);
        $this->univers = new FreeEcosystem(new Factory($popSize), $this->logger);
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("Free evolution");

        $grafx = [];
        for ($generation = 0; $generation < $this->maxGeneration; $generation++) {
            $output->writeln("======== Generation $generation ========");
            $report = $this->univers->evolve($this->round, $this->extinctRatio);
            $output->writeln($report['text']);
            $grafx[$generation] = $report['grafx'];
        }

        if (!is_null($this->plotFile)) {
            $this->logger->writeGraphic($this->plotFile);
        }
    }

}
