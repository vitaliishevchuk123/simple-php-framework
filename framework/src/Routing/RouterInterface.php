<?php

namespace SimplePhpFramework\Routing;

use Psr\Container\ContainerInterface;
use SimplePhpFramework\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request, ContainerInterface $container);

    public function registerRoutes(array $routes): void;
}
