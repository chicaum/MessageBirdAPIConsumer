<?php

use Symfony\Component\HttpFoundation\Request;

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';

$request = Request::createFromGlobals();

$kernel = new Kernel();

$response = $kernel->dispatch($request);

$response->send();
