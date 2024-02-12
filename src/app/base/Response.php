<?php

namespace app\base;

class Response
{
    private string $contents = '';
    private array  $headers = [];

    public function getContents(): string
    {
        return $this->contents;
    }

    public function setContent(string $contents): void
    {
        $this->contents = $contents;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }
}
