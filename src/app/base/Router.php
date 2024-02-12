<?php

namespace app\base;

class Router
{
    private array  $routes;
    private string $controllerName = '';
    private string $actionName     = '';

    public function __construct()
    {
        $this->routes = require base_path('config/routes.php');
    }

    public function processUri(Request $request): array
    {
        $matchedRoute = false;

        $uri = $request->getUri();

        $uriParsed = parse_url($uri);
        $path      = $uriParsed['path'] ?? null;
        $arguments = [];

        foreach ($this->routes as $route => $handler) {
            $escapedRoute = escape_string($route);

            if ($path === $route) {
                $matchedRoute = $route;
                break;
            }

            preg_match("/{$escapedRoute}/", $path, $matches);
            if (!empty($matches[1])) {
                $matchedRoute = $route;
                $arguments[]  = $matches[1];
                break;
            }
        }

        if ($request->getMethod() !== Request::METHOD_GET) {
            $arguments[] = $request;
        }

        $handler = $this->routes[$matchedRoute] ?? null;

        if (!$matchedRoute) {
            // todo 404
        }

        if (!isset($handler[$request->getMethod()])) {
            // todo 405
        }

        $parts = explode('/', $handler[$request->getMethod()]);

        return array_values(
            [
                'controllerName' => $parts[0],
                'actionName'     => $parts[1],
                'arguments'      => $arguments
            ]
        );
    }

    public function run(Request $request): void
    {
        [$controllerName, $actionName, $arguments] = $this->processUri($request);

        $response = Application::getInstance()->getResponse();

        $controllerName       = ucfirst($controllerName) . 'Controller';
        $this->controllerName = 'app\controllers\\' . $controllerName;
        $this->actionName     = $actionName;
        $controller           = new $this->controllerName;
        $controller->{$this->actionName}(...$arguments);

        foreach ($response->getHeaders() as $header => $value) {
            header("{$header}:{$value}");
        }
        echo $response->getContents();
    }

    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    public function getActionName(): string
    {
        return $this->actionName;
    }
}
