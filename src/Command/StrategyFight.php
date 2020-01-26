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
    protected $popSize = 300;

    public function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("test");

        // init pop
        $mode = ['soak', 'attack', 'armor'];
        for ($k = 0; $k < $this->popSize; $k++) {
            $strat = $mode[$k % 3];
            $pc = new Character('pc' . $k . ' ' . $strat, $strat);
            $this->population[] = $pc;
        }

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

        usort($this->population, function($a, $b) {
            return $b->getWinningCount() - $a->getWinningCount();
        });

        $stratCounter = array_combine($mode, [0, 0, 0]);
        $winCounter = array_combine($mode, [0, 0, 0]);
        foreach ($this->population as $pc) {
            $stratCounter[$pc->getVoidStrat()] ++;
            $winCounter[$pc->getVoidStrat()] += $pc->getWinningCount();
        }
        foreach ($mode as $idx) {
            $output->writeln($idx . ' ' . floor(($winCounter[$idx] / $stratCounter[$idx])));
        }
        var_dump($stratCounter, $winCounter);
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

}
