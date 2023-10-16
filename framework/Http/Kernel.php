<?php

namespace SimplePhpFramework\Http;

use SimplePhpFramework\Routing\RouterInterface;

class Kernel
{
    public function __construct(
        private readonly RouterInterface $router
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request);

            $response = call_user_func_array($routeHandler, $vars);
        } catch (\Throwable $exception) {
            $response = new Response($exception->getMessage(), 500);
        }

        return $response;
    }
}
