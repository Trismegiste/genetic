<?php

namespace Trismegiste\Genetic\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\L5r\Character;
use Trismegiste\Genetic\Game\L5r\Property\Stance;
use Trismegiste\Genetic\Game\L5r\Property\VoidStrategy;

/**
 * Search for best opponent with PC reference
 */
class L5rCompare extends L5rEvolve {

    // the name of the command
    protected static $defaultName = 'evolve:compare';
    protected $referencePop = [];
    protected $refPopPercent = 10;
    protected $round = 10;
    protected $winMargin = 0.97;

    protected function configure() {
        $this->setDescription("Compute evolution")
                ->addArgument('config', InputArgument::REQUIRED, "Config file");
    }

    public function initialize(InputInterface $input, OutputInterface $output) {
        $config = json_decode(file_get_contents($input->getArgument('config')), true);
        $this->popSize = $config['popSize'];
        $this->maxGeneration = $config['maxIter'];
        $this->opponent = $config['opponents'];
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("Darwin rules");

        foreach ($this->opponent as $opponentIdx => $opponent) {
            $output->writeln("================ OPPONENT #$opponentIdx ===============");

            // init population for evolution
            $this->population = [];
            for ($k = 0; $k < $this->popSize; $k++) {
                $pc = new Character('L5R', ['voidStrat' => VoidStrategy::getRandomStrat(), 'stance' => Stance::getRandomStrat()]);
                $this->population[] = $pc;
            }

            // init population for reference
            $this->referencePop = [];
            for ($k = 0; $k < $this->popSize * $this->refPopPercent / 100; $k++) {
                $opponent['voidStrat'] = VoidStrategy::getRandomStrat();
                $opponent['stance'] = Stance::getRandomStrat();
                $pc = new Character('L5R', $opponent);
                $this->referencePop[] = $pc;
            }
            $output->writeln("Ref: " . $this->referencePop[0]);

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
            $output->writeln("-");
        }
    }

    protected function writePopulation($gen) {
        $fch = fopen("generation-$gen.txt", "w");
        foreach ($this->population as $pc) {
            fwrite($fch, $pc->getCost() . ";" . $pc->getWinningCount() . PHP_EOL);
        }
        fclose($fch);
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
