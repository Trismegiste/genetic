<?php

namespace Trismegiste\Genetic\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\L5r\Character;
use Trismegiste\Genetic\Game\L5r\Property\Stance;
use Trismegiste\Genetic\Game\L5r\Property\VoidStrategy;

/**
 * Free evolution
 */
class L5rFree extends L5rEvolve {

    // the name of the command
    protected static $defaultName = 'evolve:free';
    protected $round;
    protected $extinctRatio;

    protected function configure() {
        $this->setDescription("Compute free evolution")
                ->addArgument('popSize', InputArgument::REQUIRED, "Population size")
                ->addArgument('maxIter', InputArgument::REQUIRED, "Max iteration")
                ->addOption('round', NULL, InputArgument::OPTIONAL, 'How many round between 2 PC', 5)
                ->addOption('extinct', NULL, InputArgument::OPTIONAL, 'Percentage of how many population are extinct between generation', 10);
    }

    public function initialize(InputInterface $input, OutputInterface $output) {
        $this->popSize = $input->getArgument('popSize');
        $this->maxGeneration = $input->getArgument('maxIter');
        $this->round = $input->getOption('round');
        $this->extinctRatio = 1 - $input->getOption('extinct') / 100.0;

        // init population for evolution
        $this->population = [];
        for ($k = 0; $k < $this->popSize; $k++) {
            $pc = new Character('L5R', [
                'voidStrat' => VoidStrategy::getRandomStrat(),
                'stance' => Stance::getRandomStrat(),
                'agility' => rand(2, 6),
                'reflexe' => rand(2, 6),
                'earth' => rand(2, 6),
                'kenjutsu' => rand(1, 5),
                'void' => rand(2, 5),
                'strength' => rand(2, 6)
            ]);
            $this->population[] = $pc;
        }
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("Free evolution");

        for ($generation = 0; $generation < $this->maxGeneration; $generation++) {
            $output->writeln("======== Generation $generation ========");
            $this->tournament();

            usort($this->population, function($a, $b) {
                return $b->getWinningCount() - $a->getWinningCount();
            });

            foreach ([0, 1, 2, 5, 9] as $idx) {
                $output->writeln("$idx - " . $this->population[$idx]);
            }

            // re-initialise pop
            foreach ($this->population as $pc) {
                $pc->newGeneration();
            }

            // select & mutate
            foreach ($this->population as $idx => $pc) {
                if ($idx > ($this->extinctRatio * $this->popSize)) {
                    // we clone & mutate the best fit to replace the worst fit
                    $npc = clone $this->population[0];
                    $npc->mutate();
                    $this->population[$idx] = $npc;
                }
            }
        }
    }

    protected function tournament() {
        foreach ($this->population as $idx1 => $pc1) {
            foreach ($this->population as $idx2 => $pc2) {
                if ($idx2 <= $idx1) {
                    continue;
                }
                $delta = $pc1->getCost() - $pc2->getCost();

                $key1 = spl_object_hash($pc1);
                $key2 = spl_object_hash($pc2);
                $win = [$key1 => 0, $key2 => 0];
                for ($k = 0; $k < $this->round; $k++) {
                    $pc1->restart();
                    $pc2->restart();
                    $winner = $this->battle($pc1, $pc2);
                    $win[spl_object_hash($winner)] ++;
                }

                if (($win[$key1] > $win[$key2]) && ($delta <= 0)) {
                    $pc1->incVictory();
                }
                if (($win[$key1] < $win[$key2]) && ($delta >= 0)) {
                    $pc2->incVictory();
                }
                // many cases are missed : equality. We don't care, we want a threshold effect
            }
        }
    }

}
