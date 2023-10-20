<?php

namespace SimplePhpFramework\Http\Middleware;

use Psr\Container\ContainerInterface;
use SimplePhpFramework\Http\Request;
use SimplePhpFramework\Http\Response;
use SimplePhpFramework\Routing\RouterInterface;

class RouterDispatch implements MiddlewareInterface
{
    public function __construct(
        private RouterInterface $router,
        private ContainerInterface $container
    ) {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);

        $response = call_user_func_array($routeHandler, $vars);

        return $response;
    }
}
