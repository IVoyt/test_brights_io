<?php

namespace app\base;

class Request
{
    public const METHOD_GET    = 'GET';
    public const METHOD_POST   = 'POST';
    public const METHOD_PUT    = 'PUT';
    public const METHOD_PATCH  = 'PATCH';
    public const METHOD_DELETE = 'DELETE';

    public const CONTENT_TYPE_HTML = 'text/html';
    public const CONTENT_TYPE_JSON = 'application/json';

    private string $contentType;
    private string $method;
    private string $uri;
    private array  $request;

    public function __construct()
    {
        $httpAccept        = explode(',', $_SERVER['HTTP_ACCEPT'] ?? '');
        $this->contentType = $httpAccept[0];
        if ($this->contentType === '*/*') {
            $this->contentType = self::CONTENT_TYPE_HTML;
        }
        $this->uri     = $_SERVER['REQUEST_URI'] ?? '/';
        $this->method  = $_SERVER['REQUEST_METHOD'] ?? self::METHOD_GET;
        $this->request = $_REQUEST;
        unset($this->request['q']);
        $requestContentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if ($requestContentType === self::CONTENT_TYPE_JSON) {
            $postData      = file_get_contents('php://input');
            $this->request = json_decode($postData, true);
        }
    }

    public function get(string $key, $defaultValue = null)
    {
        return $this->request[$key] ?? $defaultValue;
    }

    public function input(): array
    {
        return $this->request;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
