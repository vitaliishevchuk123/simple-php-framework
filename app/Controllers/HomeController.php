<?php

namespace App\Controllers;

use SimplePhpFramework\Http\Response;

class HomeController
{
    public function index(): Response
    {
        $content = '<h1>Hello, World!!!</h1>';

        return new Response($content);
    }
}
