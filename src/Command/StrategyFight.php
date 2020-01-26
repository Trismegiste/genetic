<?php

namespace Trismegiste\Genetic\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
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
    protected $popSize;
    protected $maxGeneration;

    protected function configure() {
        $this->setDescription("Compute evolution")
                ->addArgument('popSize', InputArgument::REQUIRED, "Population size")
                ->addArgument('maxIter', InputArgument::REQUIRED, "Maximum iteration");
    }

    public function initialize(InputInterface $input, OutputInterface $output) {
        $this->popSize = $input->getArgument('popSize');
        $this->maxGeneration = $input->getArgument("maxIter");
        // init pop
        for ($k = 0; $k < $this->popSize; $k++) {
            $pc = new Character('L5R');
            $this->population[] = $pc;
        }
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("Darwin rules");

        for ($generation = 0; $generation < $this->maxGeneration; $generation++) {
            $output->writeln("======== Generation $generation ========");
            $this->tournament();

            $env['maxwin'] = $this->popSize;
            usort($this->population, function($a, $b) {
                global $env;
                return $a->getFitness($env) - $b->getFitness($env);
            });
            foreach ([0, 10, 20] as $idx) {
                $output->writeln('best = ' . $this->population[$idx]);
            }

            foreach ($this->population as $idx => $pc) {
                $pc->newGeneration();
                if ($idx > $this->popSize / 10) {
                    $obj = clone $this->population[rand(0, $this->popSize / 10)];
                    $obj->mutate();
                    $this->population[$idx] = $obj;
                }
            }
        }
    }

    protected function writePopulation($gen) {
        $fch = fopen("generation-$gen.txt", "w");
        foreach ($this->population as $pc) {
            fwrite($fch, $pc->getCost() . ";" . $pc->getWinningCount() . PHP_EOL);
        }
        fclose($fch);
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

        return $pc1->isDead() ? $pc2 : $pc1;
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
                if ($winner === $pc1) {
                    $pc1->incVictory();
                } else {
                    $pc2->incVictory();
                }
            }
        }
    }

}
