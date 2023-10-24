<?php

namespace SimplePhpFramework\Routing;

use Psr\Container\ContainerInterface;
use SimplePhpFramework\Http\Request;

class Router implements RouterInterface
{
    public function dispatch(Request $request, ContainerInterface $container): array
    {
        $handler = $request->getRouteHandler();
        $vars = $request->getRouteArgs();

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

    private function processParams(array $params, ContainerInterface $container): array
    {
        return array_merge(...(array_map(function (\ReflectionParameter $reflectionParameter) use ($container) {
            $val[$reflectionParameter->getName()] = $reflectionParameter->isDefaultValueAvailable() ? $reflectionParameter->getDefaultValue() : null;
            $entity = $reflectionParameter->getType()->getName();
            if (class_exists($entity) || interface_exists($entity)) {
                $val[$reflectionParameter->getName()] = $container->get($entity);
            }
            return $val;
        }, $params)));
    }
}
