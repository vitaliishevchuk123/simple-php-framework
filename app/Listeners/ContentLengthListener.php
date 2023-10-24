<?php

namespace App\Listeners;

use SimplePhpFramework\Http\Events\ResponseEvent;

class ContentLengthListener
{
    public function __invoke(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        dump(1);

        if (! array_key_exists('Content-Length', $response->getHeaders())) {
            $response->setHeader('Content-Length', strlen($response->getContent()));
        }
    }
}
