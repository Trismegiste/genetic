<?php

namespace Trismegiste\Genetic\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Search for best strategy
 */
class StrategyFight extends Command {

    // the name of the command
    protected static $defaultName = 'search:strategy';

    public function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("test");
        $s = 0;
        for ($k = 0; $k < 1000000; $k++) {
            $s += \Trismegiste\Genetic\Game\L5r\DiceRoller::rollAndKeep(6, 3);
        }
        $output->writeln($s / 1000000);
    }

}
