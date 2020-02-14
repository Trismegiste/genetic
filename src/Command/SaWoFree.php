<?php

namespace Trismegiste\Genetic\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\SaWo\Factory;
use Trismegiste\Genetic\Game\SaWo\FreeEcosystem;
use Trismegiste\Genetic\Game\SaWo\GrafxLogger;
use Trismegiste\Genetic\Game\SaWo\TextLogger;
use Trismegiste\Genetic\Util\PlotterXY;

/**
 * Free evolution for SaWo
 */
class SaWoFree extends Command {

    protected static $defaultName = 'sawo:free';
    protected $round;
    protected $extinctRatio;
    protected $univers;
    protected $maxGeneration;
    protected $plotFile;
    protected $logger;

    protected function configure() {
        $this->setDescription("Compute free evolution for SaWo")
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
        $plotFile = $input->getOption('plot');

        if (!is_null($plotFile)) {
            $this->logger = new GrafxLogger($output, new PlotterXY(1920, 1080, $plotFile));
        } else {
            $this->logger = new TextLogger($output);
        }
        $this->univers = new FreeEcosystem(new Factory($popSize), $this->logger);
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("Free evolution");

        for ($generation = 0; $generation < $this->maxGeneration; $generation++) {
            $output->writeln("======== Generation $generation ========");
            $this->univers->evolve($this->round, $this->extinctRatio);
        }

        $this->logger->endLog();
    }

}
