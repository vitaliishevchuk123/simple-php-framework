<?php

namespace App\Controllers;

use SimplePhpFramework\Controller\AbstractController;
use SimplePhpFramework\Http\Response;

class DashboardController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('dashboard.html.twig');
    }
}
