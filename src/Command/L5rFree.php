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
                'void' => rand(2, 4),
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

            foreach ([0, 1, 2, 3, 4, 5, 9, 19, 49] as $idx) {
                $output->writeln("$idx - " . $this->population[$idx]);
            }

            foreach ($this->population as $idx => $pc) {
                if ($idx > (7 * $this->popSize / 10)) {
                    $npc = clone $this->population[rand(0, $this->popSize / 10)];
                    $npc->mutate();
                    $this->population[$idx] = $npc;
                    $pc->newGeneration();
                } else {
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
                $pc1->restart();
                $pc2->restart();
                $winner = $this->battle($pc1, $pc2);
                $delta = $pc1->getCost() - $pc2->getCost();

                if (($winner === $pc1) && ($delta < 0)) {
                    $pc1->incVictory();
                }
                if (($winner === $pc2) && ($delta > 0)) {
                    $pc2->incVictory();
                }
            }
        }
    }

}
