<?php

namespace Trismegiste\Genetic\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\L5r\FreeEcosystem;

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
        $this->extinctRatio = $input->getOption('extinct') / 100.0;

        $this->univers = new FreeEcosystem($popSize);
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
    }

    protected function writeImage() {
        $width = 1000;
        $height = 1000;
        $h = imagecreatetruecolor($width, $height);
        $background = imagecolorallocate($h, 255, 255, 255);
        $plotColor = imagecolorallocate($h, 0, 0, 0);
        imagefill($h, 0, 0, $background);

        $minWin = $minCost = 99999;
        $maxWin = $maxCost = 0;
        foreach ($this->population as $pc) {
            $c = $pc->getCost();
            $w = $pc->getWinningCount();
            if ($c < $minCost) {
                $minCost = $c;
            }
            if ($c > $maxCost) {
                $maxCost = $c;
            }
            if ($w < $minWin) {
                $minWin = $c;
            }
            if ($w > $maxWin) {
                $maxWin = $c;
            }
        }

        foreach ($this->population as $pc) {
            /*     $x = 10 + ($pc->getCost() - $minCost) * ($width - 20) / ($maxCost - $minCost);
              $y = 10 + ($pc->getWinningCount() - $minWin) * ($height - 20) / ($maxWin - $minWin); */
            imagefilledellipse($h, 3 * $pc->getCost(), $height - $height * $pc->getWinningCount() / $this->getSize(), 4, 4, $plotColor);
        }


        imagepng($h, "population" . time() . ".png");
    }

}
