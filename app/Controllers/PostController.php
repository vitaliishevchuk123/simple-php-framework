<?php

namespace App\Controllers;

use SimplePhpFramework\Http\Response;

class PostController
{
    public function show(int $id): Response
    {
        $content = "<h1>Post - $id</h1>";

        return new Response($content);
    }
}
