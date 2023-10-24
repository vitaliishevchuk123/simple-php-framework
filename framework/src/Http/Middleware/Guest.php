<?php

namespace SimplePhpFramework\Http\Middleware;

use SimplePhpFramework\Authentication\SessionAuthInterface;
use SimplePhpFramework\Http\RedirectResponse;
use SimplePhpFramework\Http\Request;
use SimplePhpFramework\Http\Response;
use SimplePhpFramework\Session\SessionInterface;

class Guest implements MiddlewareInterface
{
    public function __construct(
        private SessionAuthInterface $auth,
        private SessionInterface $session
    ) {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();

        if ($this->auth->check()) {
            return new RedirectResponse('/dashboard');
        }

        return $handler->handle($request);
    }
}
