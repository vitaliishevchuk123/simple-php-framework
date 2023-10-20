<?php

namespace App\Controllers;

use App\Entities\Post;
use App\Services\PostService;
use SimplePhpFramework\Controller\AbstractController;
use SimplePhpFramework\Http\Request;
use SimplePhpFramework\Http\Response;
use SimplePhpFramework\Http\RedirectResponse;
use SimplePhpFramework\Session\SessionInterface;

class PostController extends AbstractController
{
    public function __construct(
        private PostService $service,
        private SessionInterface $session
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

        $this->session->setFlash('success', 'Пост успішно створено!');

        return new RedirectResponse("/posts/{$post->getId()}");
    }
}
