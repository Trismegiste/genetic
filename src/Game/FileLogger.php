<?php

namespace Trismegiste\Genetic\Game;

/**
 * Backup in file
 */
class FileLogger implements PopulationLogger {

    protected $filename;

    public function __construct($fch) {
        $this->filename = $fch;
    }

    public function endLog() {
        
    }

    public function log(array &$pop) {
        
    }

}
