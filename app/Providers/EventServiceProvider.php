<?php

namespace App\Providers;

use App\Listeners\ContentLengthListener;
use App\Listeners\HandleEntityListener;
use App\Listeners\InternalErrorListener;
use SimplePhpFramework\Dbal\Event\EntityPersist;
use SimplePhpFramework\Event\EventDispatcher;
use SimplePhpFramework\Http\Events\ResponseEvent;
use SimplePhpFramework\Providers\ServiceProviderInterface;

class EventServiceProvider implements ServiceProviderInterface
{
    private array $listen = [
        ResponseEvent::class => [
            InternalErrorListener::class,
            ContentLengthListener::class,
        ],
        EntityPersist::class => [
            HandleEntityListener::class,
        ],
    ];

    public function __construct(
        private readonly EventDispatcher $eventDispatcher
    ) {
    }

    public function register(): void
    {
        foreach ($this->listen as $event => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                $this->eventDispatcher->addListener($event, new $listener);
            }
        }
    }
}
