<?php

namespace App\Controllers;

use SimplePhpFramework\Authentication\SessionAuthInterface;
use SimplePhpFramework\Controller\AbstractController;
use SimplePhpFramework\Http\RedirectResponse;
use SimplePhpFramework\Http\Request;
use SimplePhpFramework\Http\Response;

class LoginController extends AbstractController
{
    public function form(): Response
    {
        return $this->render('login.html.twig');
    }

    public function login(Request $request, SessionAuthInterface $sessionAuth): RedirectResponse
    {
        $isAuth = $sessionAuth->authenticate(
            $request->input('email'),
            $request->input('password'),
        );

        if (!$isAuth) {
            $request->getSession()->setFlash('error', 'Невірний логін або пароль');

            return new RedirectResponse('/login');
        }

        $request->getSession()->setFlash('success', 'Вхід успішно виконано!');

        return new RedirectResponse('/dashboard');
    }
}
