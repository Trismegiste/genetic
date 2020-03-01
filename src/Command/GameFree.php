<?php

namespace Trismegiste\Genetic\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\AggregateLogger;
use Trismegiste\Genetic\Game\DarwinWorld;
use Trismegiste\Genetic\Game\FileLogger;
use Trismegiste\Genetic\Game\GrafxLogger;
use Trismegiste\Genetic\Game\MutableFighterFactory;
use Trismegiste\Genetic\Game\PopulationLogger;
use Trismegiste\Genetic\Game\StatLogger;
use Trismegiste\Genetic\Game\TextLogger;
use Trismegiste\Genetic\Util\AnimateXY;
use Trismegiste\Genetic\Util\PlotterXY;

/**
 * Generic command for free evolution
 */
abstract class GameFree extends Command {

    protected function configure() {
        $this->addArgument('popSize', InputArgument::REQUIRED, "Population size")
                ->addArgument('maxIter', InputArgument::REQUIRED, "Max iteration")
                ->addOption('round', NULL, InputOption::VALUE_REQUIRED, 'How many round between 2 PC', 5)
                ->addOption('extinct', NULL, InputOption::VALUE_REQUIRED, 'Percentage of how many population are extinct between generation', 5)
                ->addOption('plot', NULL, InputOption::VALUE_REQUIRED, 'File name of plotting PNG picture')
                ->addOption('animate', NULL, InputOption::VALUE_NONE, 'Multiple PNG file for animation')
                ->addOption('stat', NULL, InputOption::VALUE_NONE, 'Create stats file')
                ->addOption('dump', NULL, InputOption::VALUE_NONE, 'Dump all data');
    }

    protected function checkPositiveInteger(InputInterface $arg, $name) {
        $val = intval($arg->getArgument($name));
        if (!($val > 0)) {
            throw new InvalidArgumentException("$name is not a positive integer");
        }
    }

    protected function initialize(InputInterface $input, OutputInterface $output) {
        $this->checkPositiveInteger($input, 'popSize');
        $this->checkPositiveInteger($input, 'maxIter');
    }

    protected function buildLogger(InputInterface $input, OutputInterface $output) {
        $extinctRatio = $input->getOption('extinct') / 100.0;
        $plotFile = $input->getOption('plot');

        $logger = new AggregateLogger([new TextLogger($output, $extinctRatio)]);
        if ($input->getOption('dump')) {
            $logger->push(new FileLogger());
        }
        if ($input->getOption('stat')) {
            $logger->push(new StatLogger($output));
        }
        if (!is_null($plotFile)) {
            if ($input->getOption('animate')) {
                $plotter = new AnimateXY(1920, 1080, $plotFile . '%04d.png');
            } else {
                $plotter = new PlotterXY(1920, 1080, $plotFile . '.png');
            }
            $logger->push(new GrafxLogger($plotter));
        }

        return $logger;
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $popSize = $input->getArgument('popSize');
        $maxGeneration = $input->getArgument('maxIter');
        $round = $input->getOption('round');
        $extinctRatio = $input->getOption('extinct') / 100.0;
        $logger = $this->buildLogger($input, $output);
        $factory = $this->buildFactory();
        $univers = $this->buildWorld($popSize, $factory, $logger);

        $output->writeln("Free evolution");

        for ($generation = 0; $generation < $maxGeneration; $generation++) {
            $output->writeln("======== Generation $generation ========");
            $univers->evolve($round, $extinctRatio);
        }

        $logger->endLog();
    }

    abstract protected function buildFactory(): MutableFighterFactory;

    abstract protected function buildWorld(int $n, MutableFighterFactory $fac, PopulationLogger $log): DarwinWorld;
}
