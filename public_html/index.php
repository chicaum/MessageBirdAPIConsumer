<?php

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';

$kernel = new Kernel('dev');

echo $kernel->getEnv();
