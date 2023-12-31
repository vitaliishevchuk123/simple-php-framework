<?php

namespace SimplePhpFramework\Http\Middleware;

use Psr\Container\ContainerInterface;
use SimplePhpFramework\Http\Request;
use SimplePhpFramework\Http\Response;

class RequestHandler implements RequestHandlerInterface
{
    private array $middleware = [
        ExtractRouteInfo::class,
        StartSession::class,
        RouterDispatch::class,
    ];

    public function __construct(
        private ContainerInterface $container
    )
    {
    }

    public function handle(Request $request): Response
    {
        // Якщо немає middleware-класів для виконання, повернути відповідь за замовчуванням
        // Відповідь повинна була бути повернена до того, як список стане порожнім

        if (empty($this->middleware)) {
            return new Response('Server error', 500);
        }

        // Отримати наступний middleware-клас для виконання

        $middlewareClass = array_shift($this->middleware);

        // Створити новий екземпляр виклику процесу middleware на ньому

        $middleware = $this->container->get($middlewareClass);

        $response = $middleware->process($request, $this);

        return $response;
    }

    public function injectMiddleware(array $middleware): void
    {
        array_splice($this->middleware, 0, 0, $middleware);
    }
}