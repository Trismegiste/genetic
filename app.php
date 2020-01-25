<?php

require __DIR__ . '/vendor/autoload.php';

use Trismegiste\Genetic\Command\StrategyFight;
use Symfony\Component\Console\Application;

$app = new Application;
$app->add(new StrategyFight());
$app->run();
