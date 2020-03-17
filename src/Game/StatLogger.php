<?php

namespace Trismegiste\Genetic\Game;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Print stats analysis
 */
class StatLogger implements PopulationLogger {

    protected $console;
    protected $percentile;
    protected $handle;

    public function __construct(OutputInterface $out, float $percentile = 0.1) {
        $this->console = $out;
        $this->percentile = $percentile;
        $this->handle = fopen('export.csv', "w");
        fputcsv($this->handle, ['best victory avg cost', 'min cost', 'max cost']);
    }

    public function endLog() {
        fclose($this->handle);
    }

    public function log(array &$pop) {
        $card = count($pop);
        $totalWin = $totalCost = 0;
        foreach ($pop as $pc) {
            $totalCost += $pc->getCost();
            $totalWin += $pc->getVictory();        
        }

        $medianWin = $medianCost = 0;
        $minCost = $totalCost;
        $maxCost = 0;
        $idx = 0;
        while ($medianWin < ($totalWin * $this->percentile)) {
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

        $this->console->writeln(sprintf("Avg win = %.1f / Avg of %.0f%% best win = %.1f (%.0f%% of population)"
                        , $totalWin / $card
                        , $this->percentile * 100
                        , $medianWin / $idx
                        , 100 * $idx / $card));
        $this->console->writeln(sprintf("Avg cost = %.1f / Avg cost of best win = %.1f / [min,max] = [%d,%d]"
                        , $totalCost / $card
                        , $medianCost / $idx
                        , $minCost, $maxCost));

        fputcsv($this->handle, [$medianCost / $idx, $minCost, $maxCost]);
    }

}
