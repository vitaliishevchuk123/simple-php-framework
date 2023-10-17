<?php

namespace SimplePhpFramework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use SimplePhpFramework\Http\Exceptions\MethodNotAllowedException;
use SimplePhpFramework\Http\Exceptions\RouteNotFoundException;
use SimplePhpFramework\Http\Request;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    /**
     * @throws RouteNotFoundException|MethodNotAllowedException
     */
    public function dispatch(Request $request): array
    {
        [$handler, $vars] = $this->extractRouteInfo($request);

        if (is_array($handler)) {
            [$controller, $method] = $handler;
            $handler = [new $controller, $method];
        }

        return [$handler, $vars];
    }

    /**
     * @throws RouteNotFoundException|MethodNotAllowedException
     */
    private function extractRouteInfo(Request $request): array
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

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                return [$routeInfo[1], $routeInfo[2]];
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                throw new MethodNotAllowedException("Supported HTTP methods: $allowedMethods");
            default:
                throw new RouteNotFoundException('Route not found');
        }
    }
}
