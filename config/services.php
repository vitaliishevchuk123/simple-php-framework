<?php

use Doctrine\DBAL\Connection;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use SimplePhpFramework\Controller\AbstractController;
use SimplePhpFramework\Dbal\ConnectionFactory;
use SimplePhpFramework\Http\Kernel;
use SimplePhpFramework\Routing\Router;
use SimplePhpFramework\Routing\RouterInterface;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$dotenv = new Dotenv();
$dotenv->load(BASE_PATH.'/.env');

// Application parameters

$routes = include BASE_PATH.'/routes/web.php';
$appEnv = $_ENV['APP_ENV'] ?? 'local';
$viewsPath = BASE_PATH.'/views';
$connectionParams = [
    'dbname' => $_ENV['DB_DATABASE'],
    'user' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'host' => $_ENV['DB_HOST'] . ':' . $_ENV['DB_PORT'],
    'driver' => $_ENV['DB_DRIVER'],
];

// Application services

$container = new Container();

$container->delegate(new ReflectionContainer(true));

$container->add('APP_ENV', new StringArgument($appEnv));

$container->add(RouterInterface::class, Router::class);

$container->extend(RouterInterface::class)
    ->addMethodCall('registerRoutes', [new ArrayArgument($routes)]);

$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);

$container->add('twig-loader', FilesystemLoader::class)
    ->addArgument(new StringArgument($viewsPath));

$container->addShared('twig', Environment::class)
    ->addArgument('twig-loader');

$container->inflector(AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

$container->add(ConnectionFactory::class)
    ->addArgument(new ArrayArgument($connectionParams));

$container->addShared(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

return $container;
