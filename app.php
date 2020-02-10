<?php

require __DIR__ . '/vendor/autoload.php';

use Trismegiste\Genetic\Command;
use Symfony\Component\Console\Application;

$app = new Application;
$app->add(new Command\L5rCompare());
$app->add(new Command\L5rFree());
$app->add(new Command\SaWoFree());
$app->run();
