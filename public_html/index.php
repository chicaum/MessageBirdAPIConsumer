<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Dotenv\Dotenv;

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';
(new Dotenv())->load(__DIR__.'/../.env');

$request = Request::createFromGlobals();

$kernel = new Kernel();

$response = $kernel->dispatch($request);

$response->send();
