<?php

use App\Services\UserService;
use Doctrine\DBAL\Connection;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use SimplePhpFramework\Authentication\SessionAuthentication;
use SimplePhpFramework\Authentication\SessionAuthInterface;
use SimplePhpFramework\Console;
use SimplePhpFramework\Console\Application;
use SimplePhpFramework\Console\Commands\MigrateCommand;
use SimplePhpFramework\Controller\AbstractController;
use SimplePhpFramework\Dbal\ConnectionFactory;
use SimplePhpFramework\Http;
use SimplePhpFramework\Http\Middleware\RequestHandler;
use SimplePhpFramework\Http\Middleware\RequestHandlerInterface;
use SimplePhpFramework\Http\Middleware\RouterDispatch;
use SimplePhpFramework\Routing\Router;
use SimplePhpFramework\Routing\RouterInterface;
use SimplePhpFramework\Session\Session;
use SimplePhpFramework\Session\SessionInterface;
use SimplePhpFramework\Template\TwigFactory;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(BASE_PATH . '/.env');

// Application parameters

$routes = include BASE_PATH . '/routes/web.php';
$appEnv = $_ENV['APP_ENV'] ?? 'local';
$viewsPath = BASE_PATH . '/views';
$connectionParams = [
    'dbname' => $_ENV['DB_DATABASE'],
    'user' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'host' => $_ENV['DB_HOST'] . ':' . $_ENV['DB_PORT'],
    'driver' => $_ENV['DB_DRIVER'],
];

// Application services

if ($_ENV['APP_ENV'] === 'local') {
    $whoops = new Whoops\Run;
    $whoops->pushHandler(new Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

$container = new Container();

$container->delegate(new ReflectionContainer(true));

$container->add('framework-commands-namespace', new StringArgument('SimplePhpFramework\\Console\\Commands\\'));
$container->add('app-commands-namespace', new StringArgument('App\\Console\\Commands\\'));

$container->add('APP_ENV', new StringArgument($appEnv));

$container->add(RouterInterface::class, Router::class);

$container->extend(RouterInterface::class)
    ->addMethodCall('registerRoutes', [new ArrayArgument($routes)]);

$container->add(RequestHandlerInterface::class, RequestHandler::class)
    ->addArgument($container);

$container->add(Http\Kernel::class)
    ->addArguments([
        $container,
        RequestHandlerInterface::class,
    ]);

$container->addShared(SessionInterface::class, Session::class);

$container->add('twig-factory', TwigFactory::class)
    ->addArguments([new StringArgument($viewsPath), SessionInterface::class]);

$container->addShared('twig', function () use ($container) {
    return $container->get('twig-factory')->create();
});

$container->inflector(AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

$container->add(ConnectionFactory::class)
    ->addArgument(new ArrayArgument($connectionParams));

$container->addShared(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

$container->add(Application::class)
    ->addArgument($container);

$container->add(Console\Kernel::class)
    ->addArgument($container)
    ->addArgument(Application::class);

$container->add('console:migrate', MigrateCommand::class)
    ->addArgument(Connection::class)
    ->addArgument(new StringArgument(BASE_PATH . '/database/migrations'));

$container->add(RouterDispatch::class)
    ->addArguments([
        RouterInterface::class,
        $container,
    ]);

$container->add(SessionAuthInterface::class, SessionAuthentication::class)
    ->addArguments([
        UserService::class,
        SessionInterface::class,
    ]);

return $container;
