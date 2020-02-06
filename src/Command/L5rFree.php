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
    protected $round = 5;

    protected function configure() {
        $this->setDescription("Compute free evolution")
                ->addArgument('popSize', InputArgument::REQUIRED, "Population size")
                ->addArgument('maxIter', InputArgument::REQUIRED, "Max iteration");
    }

    public function initialize(InputInterface $input, OutputInterface $output) {
        $this->popSize = $input->getArgument('popSize');
        $this->maxGeneration = $input->getArgument('maxIter');

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

            foreach ($this->population as $idx => $pc) {
                if ($idx <= ($this->popSize / 10)) {
                    // we keep the best fit unchanged
                    $pc->newGeneration();
                } else if ($idx > (9 * $this->popSize / 10)) {
                    // we clone & mutate the best fit to replace the worst fit
                    $npc = clone $this->population[rand(0, $this->popSize / 10)];
                    $npc->mutate();
                    $npc->newGeneration();
                    $this->population[$idx] = $npc;
                } else {
                    // we mutate others
                    $pc->mutate();
                    $pc->newGeneration();
                }
            }
        }
    }

    protected function tournament() {
        foreach ($this->population as $pc1) {
            foreach ($this->population as $pc2) {
                if ($pc1 === $pc2) {
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
