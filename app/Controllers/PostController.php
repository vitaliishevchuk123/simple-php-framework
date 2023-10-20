<?php

namespace App\Controllers;

use App\Entities\Post;
use App\Services\PostService;
use SimplePhpFramework\Controller\AbstractController;
use SimplePhpFramework\Http\RedirectResponse;
use SimplePhpFramework\Http\Request;
use SimplePhpFramework\Http\Response;

class PostController extends AbstractController
{
    public function __construct(
        private PostService $service,
    ) {
    }

    public function show(int $id): Response
    {
        $post = $this->service->findOrFail($id);

        return $this->render('post.html.twig', [
            'post' => $post,
        ]);
    }

    public function create(): Response
    {
        return $this->render('create_post.html.twig');
    }

    public function store(Request $request)
    {
        $post = Post::create(
            $request->postData['title'],
            $request->postData['body'],
        );

        $post = $this->service->save($post);

        $request->getSession()->setFlash('success', 'Пост успішно створено!');

        return new RedirectResponse("/posts/{$post->getId()}");
    }
}
