<?php

namespace App\Listeners;

use SimplePhpFramework\Http\Events\ResponseEvent;

class ContentLengthListener
{
    public function __invoke(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        if (!array_key_exists('X-Content-Length', $response->getHeaders())) {
            $response->setHeader('X-Content-Length', strlen($response->getContent()));
        }
    }
}
