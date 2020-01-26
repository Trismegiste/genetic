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
    protected $popSize = 200;
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
            $regression = $this->bestFit();

            usort($this->population, function($a, $b) {
                global $regression;
                $distanceA = ($regression['a'] + $regression['b'] * $a->getCost() - $a->getWinningCount()) / sqrt(1 + $regression['b']);
                $distanceB = ($regression['a'] + $regression['b'] * $b->getCost() - $b->getWinningCount()) / sqrt(1 + $regression['b']);

                return $distanceB - $distanceA;
            });
            $output->writeln('best = ' . $this->population[0]);
            $output->writeln('best = ' . $this->population[10]);
            $output->writeln('best = ' . $this->population[30]);

            if (false) {
                // write
                $fch = fopen("generation-$generation.txt", "w");
                foreach ($this->population as $pc) {
                    fwrite($fch, $pc->getCost() . ";" . $pc->getWinningCount() . PHP_EOL);
                }
                fclose($fch);
            }

            foreach ($this->population as $idx => $pc) {
                $pc->newGeneration();
                if ($idx > $this->popSize / 2) {
                    $obj = clone $this->population[rand(0, $this->popSize / 10)];
                    $obj->mutate();
                    $this->population[$idx] = $obj;
                }
            }
        }
    }

    protected function bestFit() {
        // ln(win) avg
        $winAvg = 0;
        $costAvg = 0;
        foreach ($this->population as $pc) {
            $winAvg += $pc->getWinningCount();
            $costAvg += $pc->getCost();
        }
        $winAvg /= $this->popSize;
        $costAvg /= $this->popSize;

        $covariance = 0;
        $variance = 0;
        foreach ($this->population as $pc) {
            $covariance += ($pc->getCost() - $costAvg) * ($pc->getWinningCount() - $winAvg);
            $variance += pow($pc->getCost() - $costAvg, 2);
        }
        $slope = $covariance / $variance;
        $a = $winAvg - $slope * $costAvg;

        return ['a' => $a, 'b' => $slope];
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
