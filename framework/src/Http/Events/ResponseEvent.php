<?php

namespace SimplePhpFramework\Http\Events;

use SimplePhpFramework\Event\Event;
use SimplePhpFramework\Http\Request;
use SimplePhpFramework\Http\Response;

class ResponseEvent extends Event
{
    public function __construct(
        private readonly Request $request,
        private readonly Response $response
    ) {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
