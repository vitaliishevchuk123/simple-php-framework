<?php

namespace SimplePhpFramework\Http\Middleware;

use SimplePhpFramework\Http\Request;
use SimplePhpFramework\Http\Response;
use SimplePhpFramework\Session\SessionInterface;

class StartSession implements MiddlewareInterface
{
    public function __construct(
        private SessionInterface $session
    ) {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();

        $request->setSession($this->session);

        return $handler->handle($request);
    }
}
