<?php

namespace SimplePhpFramework\Http\Middleware;

use SimplePhpFramework\Authentication\SessionAuthInterface;
use SimplePhpFramework\Http\RedirectResponse;
use SimplePhpFramework\Http\Request;
use SimplePhpFramework\Http\Response;
use SimplePhpFramework\Session\SessionInterface;

class Authenticate implements MiddlewareInterface
{
    public function __construct(
        private SessionAuthInterface $auth,
        private SessionInterface $session
    ) {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();

        if (! $this->auth->check()) {
            $this->session->setFlash('error', 'To get started, you need to sign in to your account');

            return new RedirectResponse('/login');
        }

        return $handler->handle($request);
    }
}
