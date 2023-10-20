<?php

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

use SimplePhpFramework\Http\Kernel;
use SimplePhpFramework\Http\Request;

/** @var \League\Container\Container $container */
$container = require BASE_PATH.'/config/services.php';

$request = Request::createFromGlobals();
$container->addShared(Request::class, $request);

$kernel = $container->get(Kernel::class);

$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);
