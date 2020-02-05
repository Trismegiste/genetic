<?php

namespace Trismegiste\Genetic\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\L5r\Character;
use Trismegiste\Genetic\Game\L5r\Property\Stance;
use Trismegiste\Genetic\Game\L5r\Property\VoidStrategy;

/**
 * Search for best strategy
 */
class StrategyFight extends Command {

    // the name of the command
    protected static $defaultName = 'search:strategy';
    protected $population = [];
    protected $popSize;
    protected $maxGeneration;
    protected $referencePop = [];
    protected $refPopPercent = 10;
    protected $round = 10;
    protected $winMargin = 0.97;

    protected function configure() {
        $this->setDescription("Compute evolution")
                ->addArgument('config', InputArgument::REQUIRED, "COnfig file");
    }

    public function initialize(InputInterface $input, OutputInterface $output) {
        $config = json_decode(file_get_contents($input->getArgument('config')));
        $this->popSize = $config->popSize;
        $this->maxGeneration = $config->maxIter;
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("Darwin rules");

        // init population for evolution
        for ($k = 0; $k < $this->popSize; $k++) {
            $pc = new Character('L5R', ['voidStrat' => VoidStrategy::getRandomStrat(), 'stance' => Stance::getRandomStrat()]);
            $this->population[] = $pc;
        }

        // init population for reference
        for ($k = 0; $k < $this->popSize * $this->refPopPercent / 100; $k++) {
            $pc = new Character('L5R', ['voidStrat' => VoidStrategy::getRandomStrat(), 'stance' => Stance::getRandomStrat()]);
            $this->referencePop[] = $pc;
        }

        for ($generation = 0; $generation < $this->maxGeneration; $generation++) {
            $output->writeln("======== Generation $generation ========");
            $this->tournament();

            usort($this->population, function($a, $b) {
                if (($this->winMargin * $b->getWinningCount()) > $a->getWinningCount()) {
                    return 1;
                }
                if ($b->getWinningCount() < ($this->winMargin * $a->getWinningCount())) {
                    return -1;
                }

                return $a->getCost() - $b->getCost();
            });
            foreach ([0, 1, 2, 5, 9] as $idx) {
                $output->writeln("$idx - " . $this->population[$idx]);
            }

            //   $this->writePopulation($generation);
            foreach ($this->population as $idx => $pc) {
                if ($idx > $this->popSize / 2) {
                    $pc = clone $this->population[rand(0, $this->popSize / 10)];
                    $pc->mutate();
                    $this->population[$idx] = $pc;
                }
                $pc->newGeneration();
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
        foreach ($this->population as $pc1) {
            foreach ($this->referencePop as $pc2) {
                for ($k = 0; $k < $this->round; $k++) {
                    $pc1->restart();
                    $pc2->restart();
                    $winner = $this->battle($pc1, $pc2);
                    $winner->incVictory();
                }
            }
        }
    }

}
