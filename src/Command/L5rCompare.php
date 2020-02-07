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
class L5rCompare extends \Symfony\Component\Console\Command\Command {

    // the name of the command
    protected static $defaultName = 'evolve:compare';
    protected $referencePop = [];
    protected $refPopPercent = 10;
    protected $round = 10;
    protected $winMargin = 0.97;
    protected $popSize;
    protected $maxGeneration;

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
            $univers = new \Trismegiste\Genetic\Game\L5r\ComparedEcosystem($this->popSize, $opponent, $this->refPopPercent * $this->popSize / 100);
            $output->writeln("Ref: " . $univers->getFirstReference());

            for ($generation = 0; $generation < $this->maxGeneration; $generation++) {
                $output->writeln("======== Generation $generation ========");
                $report = $univers->evolve($this->round, 0.1);

                $output->writeln($report);
            }
            $output->writeln("-");
        }
    }

}
