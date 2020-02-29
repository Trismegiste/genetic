<?php

namespace Trismegiste\Genetic\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\AggregateLogger;
use Trismegiste\Genetic\Game\GrafxLogger;
use Trismegiste\Genetic\Game\TextLogger;
use Trismegiste\Genetic\Game\Vda\CharacterFactory;
use Trismegiste\Genetic\Game\Vda\FreeEvolution;
use Trismegiste\Genetic\Util\AnimateXY;
use Trismegiste\Genetic\Util\PlotterXY;

/**
 * Free evolution for VDA
 */
class VdaFree extends Command {

    protected static $defaultName = 'vda:free';
    protected $round;
    protected $extinctRatio;
    protected $univers;
    protected $maxGeneration;
    protected $plotFile;
    protected $logger;

    protected function configure() {
        $this->setDescription("Compute free evolution for VDA")
                ->addArgument('popSize', InputArgument::REQUIRED, "Population size")
                ->addArgument('maxIter', InputArgument::REQUIRED, "Max iteration")
                ->addOption('round', NULL, InputOption::VALUE_REQUIRED, 'How many round between 2 PC', 5)
                ->addOption('extinct', NULL, InputOption::VALUE_REQUIRED, 'Percentage of how many population are extinct between generation', 5)
                ->addOption('plot', NULL, InputOption::VALUE_REQUIRED, 'File name (without extension) of plotting PNG picture')
                ->addOption('animate', NULL, InputOption::VALUE_NONE, 'Multiple PNG file for animation');
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

    public function start(InputInterface $input, OutputInterface $output) {
        $popSize = $input->getArgument('popSize');
        $this->maxGeneration = $input->getArgument('maxIter');
        $this->round = $input->getOption('round');
        $this->extinctRatio = $input->getOption('extinct') / 100.0;
        $plotFile = $input->getOption('plot');

        $this->logger = new AggregateLogger([new TextLogger($output, $this->extinctRatio)]);
        if (!is_null($plotFile)) {
            if ($input->getOption('animate')) {
                $plotter = new AnimateXY(1920, 1080, $plotFile . '%04d.png');
            } else {
                $plotter = new PlotterXY(1920, 1080, $plotFile . '.png');
            }
            $this->logger->push(new GrafxLogger($plotter));
        }

        $this->univers = new FreeEvolution($popSize, new CharacterFactory(), $this->logger);
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $this->start($input, $output);
        $output->writeln("Free evolution");

        for ($generation = 0; $generation < $this->maxGeneration; $generation++) {
            $output->writeln("======== Generation $generation ========");
            $this->univers->evolve($this->round, $this->extinctRatio);
        }

        $this->logger->endLog();
    }

}
