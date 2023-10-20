<?php

namespace App\Controllers;

use SimplePhpFramework\Controller\AbstractController;
use SimplePhpFramework\Http\Request;
use SimplePhpFramework\Http\Response;

class RegisterController extends AbstractController
{
    public function form(): Response
    {
        return $this->render('register.html.twig');
    }

    public function register(Request $request)
    {
        dd($request);
    }
}
