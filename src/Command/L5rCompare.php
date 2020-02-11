<?php

namespace Trismegiste\Genetic\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Trismegiste\Genetic\Game\L5r\ComparedEcosystem;
use Trismegiste\Genetic\Game\L5r\Factory;

/**
 * Search for best opponent with PC reference
 */
class L5rCompare extends Command {

    // the name of the command
    protected static $defaultName = 'l5r:compare';
    protected $refPopPercent = 10;
    protected $round;
    protected $popSize;
    protected $maxGeneration;
    protected $extinctRatio;

    protected function configure() {
        $this->setDescription("Compute evolution")
                ->addArgument('config', InputArgument::REQUIRED, "Config file");
    }

    public function initialize(InputInterface $input, OutputInterface $output) {
        $config = json_decode(file_get_contents($input->getArgument('config')), true);
        $this->popSize = $config['popSize'];
        $this->maxGeneration = $config['maxIter'];
        $this->opponent = $config['opponents'];
        $this->round = $config['round'];
        $this->extinctRatio = $config['extinct'];
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("Darwin rules");
        $factory = new Factory($this->popSize);

        foreach ($this->opponent as $opponentIdx => $opponent) {
            $output->writeln("================ OPPONENT #$opponentIdx ===============");
            $univers = new ComparedEcosystem($factory, $opponent, $this->refPopPercent * $this->popSize / 100);

            for ($generation = 0; $generation < $this->maxGeneration; $generation++) {
                $output->writeln("======== Generation $generation ========");
                $report = $univers->evolve($this->round, $this->extinctRatio);
            }
            $output->writeln($report);
            $output->writeln("-");
        }
    }

}
