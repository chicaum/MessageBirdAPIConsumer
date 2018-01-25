<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';
(new Dotenv())->load(__DIR__.'/../.env');

$request = Request::createFromGlobals();

$kernel = new Kernel(getenv('APP_ENV'));

$response = $kernel->dispatch($request);

$response->send();
