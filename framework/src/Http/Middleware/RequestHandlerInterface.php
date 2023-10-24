<?php

namespace SimplePhpFramework\Http\Middleware;

use SimplePhpFramework\Http\Request;
use SimplePhpFramework\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;

    public function injectMiddleware(array $middleware): void;
}
