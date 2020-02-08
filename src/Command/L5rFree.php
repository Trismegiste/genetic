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
    protected $plotFile;

    protected function configure() {
        $this->setDescription("Compute free evolution")
                ->addArgument('popSize', InputArgument::REQUIRED, "Population size")
                ->addArgument('maxIter', InputArgument::REQUIRED, "Max iteration")
                ->addOption('round', NULL, InputArgument::OPTIONAL, 'How many round between 2 PC', 5)
                ->addOption('extinct', NULL, InputArgument::OPTIONAL, 'Percentage of how many population are extinct between generation', 10)
                ->addOption('plot', NULL, InputArgument::OPTIONAL, 'Percentage of how many population are extinct between generation', 'generation.png');
    }

    public function initialize(InputInterface $input, OutputInterface $output) {
        $popSize = $input->getArgument('popSize');
        $this->maxGeneration = $input->getArgument('maxIter');
        $this->round = $input->getOption('round');
        $this->extinctRatio = $input->getOption('extinct') / 100.0;
        $this->plotFile = $input->getOption('plot');

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

        if (!is_null($this->plotFile)) {
            $this->writeImage($grafx);
        }
    }

    protected function writeImage($data) {
        $width = 1920;
        $height = 1080;
        $h = imagecreatetruecolor($width, $height);
        $background = imagecolorallocate($h, 0, 0, 0);
        imagefill($h, 0, 0, $background);

        $minWin = $minCost = 99999;
        $maxWin = $maxCost = 0;
        foreach ($data as $generation) {
            foreach ($generation as $plot) {
                $c = $plot['c'];
                $w = $plot['w'];
                if ($c < $minCost) {
                    $minCost = $c;
                }
                if ($c > $maxCost) {
                    $maxCost = $c;
                }
                if ($w < $minWin) {
                    $minWin = $w;
                }
                if ($w > $maxWin) {
                    $maxWin = $w;
                }
            }
        }

        $deltaX = $minCost;
        $deltaY = $minWin;
        $scaleX = ($maxCost - $minCost) / ($width * 0.9);
        $scaleY = ($maxWin - $minWin) / ($height * 0.9);

        foreach ($data as $generation) {
            $plotColor = imagecolorallocate($h, mt_rand(127, 255), mt_rand(127, 255), mt_rand(127, 255));
            foreach ($generation as $plot) {
                $x = $width * 0.05 + ($plot['c'] - $deltaX) / $scaleX;
                $y = $height - ($height * 0.05 + ($plot['w'] - $deltaY) / $scaleY);
                imagefilledellipse($h, $x, $y, 4, 4, $plotColor);
            }
            imagecolordeallocate($h, $plotColor);
        }

        imagepng($h, "population.png");
        imagedestroy($h);
    }

}
