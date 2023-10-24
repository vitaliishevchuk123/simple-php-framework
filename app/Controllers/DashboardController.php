<?php

namespace App\Controllers;

use SimplePhpFramework\Authentication\SessionAuthInterface;
use SimplePhpFramework\Controller\AbstractController;
use SimplePhpFramework\Http\Response;

class DashboardController extends AbstractController
{
    public function index(SessionAuthInterface $auth): Response
    {
        return $this->render('dashboard.html.twig');
    }
}
