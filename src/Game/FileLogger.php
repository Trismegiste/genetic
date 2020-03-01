<?php

namespace Trismegiste\Genetic\Game;

/**
 * Backup in file
 */
class FileLogger implements PopulationLogger {

    protected $pattern;
    protected $generation = 0;

    public function __construct($prefix = "pcdata") {
        $this->pattern = "$prefix-%04d.txt";
    }

    public function endLog() {
        
    }

    public function log(array &$pop) {
        $handle = fopen(sprintf($this->pattern, $this->generation), "w");
        foreach ($pop as $idx => $pc) {
            fprintf($handle, "%d - %s\n", $idx, $pc);
        }
        fclose($handle);
        $this->generation++;
    }

}
