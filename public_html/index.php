<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';

(new Dotenv())->load(__DIR__.'/../.env');

$request = Request::createFromGlobals();

$content = $request->getContent();

$kernel = new Kernel('dev');

$apiKeyLive = getenv('SMS_API_KEY_LIVE');
$apiKeyTest = getenv('SMS_API_KEY_TEST');

$response = new JsonResponse(
    [
        '$apiKeyLive' => $apiKeyLive,
        '$apiKeyTest' => $apiKeyTest,
    ]
);
$response->send();
