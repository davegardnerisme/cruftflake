#!/usr/bin/php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Davegardnerisme\CruftFlake\Timer;
use Davegardnerisme\CruftFlake\Generator;
use Davegardnerisme\CruftFlake\FixedConfig;

$opts = getopt('n:');
$n = isset($opts['n']) ? (int)$opts['n'] : 1;
$n = $n < 0 ? 1 : $n;

$config    = new FixedConfig(0);
$generator = new Generator($config, new Timer());

for ($i=0; $i<$n; $i++) {
    echo $generator->generate(), PHP_EOL;
}