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
    public function index(Request $request, PostService $service)
    {
        $posts = $service->findAll();
        return $this->render('posts.html.twig', [
            'posts' => $posts,
        ]);
    }

    public function show(int $id, PostService $service): Response
    {
        $post = $service->findOrFail($id);

        return $this->render('post.html.twig', [
            'post' => $post,
        ]);
    }

    public function create(): Response
    {
        return $this->render('create_post.html.twig');
    }

    public function store(Request $request, PostService $service)
    {
        $post = Post::create(
            $request->input('title'),
            $request->input('body'),
        );

        $post = $service->save($post);

        $request->getSession()->setFlash('success', 'Пост успішно створено!');

        return new RedirectResponse("/posts/{$post->getId()}");
    }
}
