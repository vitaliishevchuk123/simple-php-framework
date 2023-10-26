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
        $currentPage = (int)$request->get('page', 1);
        if ($currentPage < 1) {
            return new RedirectResponse("/posts?page=1");
        }
        $perPage = 10;

        $posts = $service->getPostsForPage($currentPage, $perPage);
        $totalPosts = $service->getTotalPostsCount();
        $totalPages = ceil($totalPosts / $perPage);
        if ($currentPage > $totalPages) {
            return new RedirectResponse("/posts?page=1");
        }

        return $this->render('posts.html.twig', [
            'posts' => $posts,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
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
