<?php

namespace Trismegiste\Genetic\Game;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Print stats analysis
 */
class StatLogger implements PopulationLogger {

    protected $console;

    public function __construct(OutputInterface $out) {
        $this->console = $out;
    }

    public function endLog() {
        
    }

    public function log(array &$pop) {
        $card = count($pop);
        $totalWin = $totalCost = 0;
        foreach ($pop as $pc) {
            $totalCost += $pc->getCost();
            $totalWin += $pc->getVictory();
        }

        $medianWin = $medianCost = 0;
        $minCost = $maxCost = $totalCost / $card;
        $idx = 0;
        while ($medianWin < ($totalWin / 2)) {
            $pc = $pop[$idx];
            $cost = $pc->getCost();
            if ($cost > $maxCost) {
                $maxCost = $cost;
            }
            if ($cost < $minCost) {
                $minCost = $cost;
            }
            $medianWin += $pc->getVictory();
            $medianCost += $cost;
            $idx++;
        }

        $this->console->writeln(sprintf("Avg win = %.1f / Median win = %.1f", $totalWin / $card, $medianWin / $idx));
        $this->console->writeln(sprintf("Avg cost = %.1f / Median cost = %.1f / [min,max] = [%d,%d]", $totalCost / $card, $medianCost / $idx, $minCost, $maxCost));
    }

}
