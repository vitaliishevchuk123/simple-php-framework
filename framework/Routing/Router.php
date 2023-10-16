<?php

namespace SimplePhpFramework\Routing;

use FastRoute\RouteCollector;
use SimplePhpFramework\Http\Request;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    public function dispatch(Request $request): array
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            $routes = include BASE_PATH . '/routes/web.php';

            foreach ($routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPath(),
        );

        [$status, [$controller, $method], $vars] = $routeInfo;

        return [[new $controller, $method], $vars];
    }
}
