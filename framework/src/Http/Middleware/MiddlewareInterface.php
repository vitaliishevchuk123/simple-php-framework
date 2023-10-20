<?php

namespace SimplePhpFramework\Http\Middleware;

use SimplePhpFramework\Http\Request;
use SimplePhpFramework\Http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response;
}
