<?php

namespace Trismegiste\Genetic\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\L5r\Character;

/**
 * Search for best strategy
 */
class StrategyFight extends Command {

    // the name of the command
    protected static $defaultName = 'search:strategy';
    protected $population = [];
    protected $popSize = 100;
    protected $maxGeneration = 300;

    public function initialize(InputInterface $input, OutputInterface $output) {
        // init pop
        for ($k = 0; $k < $this->popSize; $k++) {
            $pc = new Character('pc' . $k);
            $this->population[] = $pc;
        }
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("Darwin rules");

        for ($generation = 0; $generation < $this->maxGeneration; $generation++) {
            $output->writeln("======== Generation $generation ========");
            $this->tournament();

            usort($this->population, function($a, $b) {
                return $b->getFitness() - $a->getFitness();
            });

            $output->writeln('best = ' . $this->population[0]);

            foreach ($this->population as $idx => $pc) {
                $pc->newGeneration();
                if ($idx > $this->popSize / 2) {
                    $pc->mutate();
                }
            }
        }
    }

    protected function battle(Character $pc1, Character $pc2) {
        $player = [];

        $init1 = $pc1->rollInit();
        $init2 = $pc2->rollInit();

        if ($init1 === $init2) {
            if (rand(1, 2) === 1) {
                $init1++;
            }
        }

        if ($init1 >= $init2) {
            $player = [$pc1, $pc2];
        } else {
            $player = [$pc2, $pc1];
        }

        while (!$pc1->isDead() && !$pc2->isDead()) {
            if (!$player[0]->isDead()) {
                $player[1]->receiveAttack($player[0]);
            }
            if (!$player[1]->isDead()) {
                $player[0]->receiveAttack($player[1]);
            }
        }

        return $pc1->isDead() ? $pc2->getName() : $pc1->getName();
    }

    protected function tournament() {
        for ($i = 0; $i < $this->popSize; $i++) {
            $pc1 = $this->population[$i];
            for ($j = 0; $j < $this->popSize; $j++) {
                if ($i === $j) {
                    continue;
                }
                $pc2 = $this->population[$j];
                $pc1->restart();
                $pc2->restart();
                $winner = $this->battle($pc1, $pc2);
                if ($winner === $pc1->getName()) {
                    $pc1->incVictory();
                } else {
                    $pc2->incVictory();
                }
            }
        }
    }

}
