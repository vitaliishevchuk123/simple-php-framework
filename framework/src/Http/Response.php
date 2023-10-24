<?php

namespace SimplePhpFramework\Http;

class Response
{
    public function __construct(
        private string $content = '',
        private int    $statusCode = 200,
        private array  $headers = [],
    )
    {
        http_response_code($this->statusCode);
    }

    public function send(): void
    {
        echo $this->content;
    }

    public function setContent(string $content): Response
    {
        $this->content = $content;

        return $this;
    }

    public function getHeader(string $key)
    {
        return $this->headers[$key];
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeader(string $key, mixed $value): void
    {
        $this->headers[$key] = $value;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}
