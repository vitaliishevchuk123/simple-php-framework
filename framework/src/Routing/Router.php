<?php

namespace SimplePhpFramework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use League\Container\Container;
use SimplePhpFramework\Http\Exceptions\MethodNotAllowedException;
use SimplePhpFramework\Http\Exceptions\RouteNotFoundException;
use SimplePhpFramework\Http\Request;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    private array $routes;

    public function dispatch(Request $request, Container $container): array
    {
        [$handler, $vars] = $this->extractRouteInfo($request);

        if (is_array($handler)) {
            [$controllerId, $method] = $handler;
            $controller = $container->get($controllerId);
            $handler = [$controller, $method];
            $reflectionClass = new \ReflectionClass($controller);
            $method = $reflectionClass->getMethod($method);
            $parameters = $this->processParams($method->getParameters(), $container);
        } else {
            $callbackFunction = new \ReflectionFunction($handler);
            $parameters = $this->processParams($callbackFunction->getParameters(), $container);
        }

        $vars = array_merge($parameters, $vars);
        return [$handler, $vars];
    }

    public function registerRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    private function extractRouteInfo(Request $request): array
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->routes as $route) {
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
                $e = new MethodNotAllowedException("Supported HTTP methods: $allowedMethods");
                $e->setStatusCode(405);
                throw $e;
            default:
                $e = new RouteNotFoundException('Route not found');
                $e->setStatusCode(404);
                throw $e;
        }
    }

    private function processParams(array $params, Container $container): array
    {
        return array_merge(...(array_map(function (\ReflectionParameter $reflectionParameter) use ($container) {
            $val[$reflectionParameter->getName()] = $reflectionParameter->isDefaultValueAvailable() ? $reflectionParameter->getDefaultValue() : null;
            if (class_exists($reflectionParameter->getType()->getName())) {
                $val[$reflectionParameter->getName()] = $container->get($reflectionParameter->getType()->getName());
            }
            return $val;
        }, $params)));
    }
}
