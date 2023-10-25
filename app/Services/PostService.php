<?php

namespace App\Services;

use App\Entities\Post;
use SimplePhpFramework\Dbal\EntityService;
use SimplePhpFramework\Http\Exceptions\NotFoundException;

class PostService
{
    public function __construct(
        private EntityService $service
    )
    {
    }

    public function save(Post $post): Post
    {
        $queryBuilder = $this->service->getConnection()->createQueryBuilder();

        $queryBuilder
            ->insert('posts')
            ->values([
                'title' => ':title',
                'body' => ':body',
                'created_at' => ':created_at',
            ])
            ->setParameters([
                'title' => $post->getTitle(),
                'body' => $post->getBody(),
                'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
            ])->executeQuery();

        $this->service->save($post);

        return $post;
    }

    public function find(int $id): ?Post
    {
        $queryBuilder = $this->service->getConnection()->createQueryBuilder();

        $result = $queryBuilder->select('*')
            ->from('posts')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery();

        $post = $result->fetchAssociative();

        if (!$post) {
            return null;
        }

        return Post::create(
            title: $post['title'],
            body: $post['body'],
            id: $post['id'],
            createdAt: new \DateTimeImmutable($post['created_at']),
        );
    }

    public function findAll(): array
    {
        $queryBuilder = $this->service->getConnection()->createQueryBuilder();

        $result = $queryBuilder->select('*')
            ->from('posts')
            ->executeQuery();

        $posts = [];

        while ($post = $result->fetchAssociative()) {
            $posts[] = Post::create(
                title: $post['title'],
                body: $post['body'],
                id: $post['id'],
                createdAt: new \DateTimeImmutable($post['created_at']),
            );
        }

        return $posts;
    }

    public function getPostsForPage(int $page, int $perPage): array
    {
        $queryBuilder = $this->service->getConnection()->createQueryBuilder();

        $queryBuilder
            ->select('*')
            ->from('posts')
            ->addOrderBy('created_at', 'desc')
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        $result = $queryBuilder->executeQuery();
        $posts = $result->fetchAllAssociative();

        $formattedPosts = [];

        foreach ($posts as $post) {
            $formattedPosts[] = Post::create(
                title: $post['title'],
                body: $post['body'],
                id: $post['id'],
                createdAt: new \DateTimeImmutable($post['created_at'])
            );
        }

        return $formattedPosts;
    }

    public function getTotalPostsCount(): int
    {
        $queryBuilder = $this->service->getConnection()->createQueryBuilder();

        $queryBuilder
            ->select('COUNT(id) as post_count')
            ->from('posts');

        $result = $queryBuilder->executeQuery();
        $row = $result->fetchAssociative();

        return (int)$row['post_count'];
    }

    public function findOrFail(int $id): Post
    {
        $post = $this->find($id);

        if (is_null($post)) {
            throw new NotFoundException("Post $id not found");
        }

        return $post;
    }
}
