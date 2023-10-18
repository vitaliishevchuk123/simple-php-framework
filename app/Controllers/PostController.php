<?php

namespace App\Controllers;

use SimplePhpFramework\Controller\AbstractController;
use SimplePhpFramework\Http\Response;

class PostController extends AbstractController
{
    public function show(int $id): Response
    {
        return $this->render('posts.html.twig', [
            'postId' => $id,
        ]);
    }
}
