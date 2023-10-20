<?php

namespace SimplePhpFramework\Http;

use League\Container\Container;
use SimplePhpFramework\Http\Exceptions\HttpException;
use SimplePhpFramework\Http\Middleware\RequestHandlerInterface;

class Kernel
{
    private string $appEnv = 'local';

    public function __construct(
        private Container               $container,
        private RequestHandlerInterface $requestHandler
    )
    {
        $this->appEnv = $this->container->get('APP_ENV');
    }

    public function handle(Request $request): Response
    {
        try {
            $response = $this->requestHandler->handle($request);
        } catch (\Throwable $e) {
            $response = $this->createExceptionResponse($e);
        }

        return $response;
    }

    private function createExceptionResponse(\Throwable $e): Response
    {
        if (in_array($this->appEnv, ['local', 'testing'])) {
            throw $e;
        }

        if ($e instanceof HttpException) {
            return new Response($e->getMessage(), $e->getStatusCode());
        }

        return new Response('Server error', 500);
    }
}
