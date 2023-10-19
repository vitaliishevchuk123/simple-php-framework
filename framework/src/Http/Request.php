<?php

namespace SimplePhpFramework\Http;

class Request
{
    public function __construct(
        public readonly array $getParams,
        public readonly array $postData,
        private readonly array $cookies,
        private readonly array $files,
        private readonly array $server,
    )
    {
    }

    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    public function getPath(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }
}
