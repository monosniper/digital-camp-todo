<?php

namespace App\Core;

class Router
{
    protected array $routes = [];

    public function get(string $uri, callable|array $handler)
    {
        $this->addRoute('GET', $uri, $handler);
    }

    public function post(string $uri, callable|array $handler)
    {
        $this->addRoute('POST', $uri, $handler);
    }

    public function patch(string $uri, callable|array $handler)
    {
        $this->addRoute('PATCH', $uri, $handler);
    }

    public function delete(string $uri, callable|array $handler): void
    {
        $this->addRoute('DELETE', $uri, $handler);
    }


    public function addRoute(string $method, string $uri, callable|array $handler)
    {
        $this->routes[] = (object)[
            'method' => strtoupper($method),
            'uri' => $uri,
            'handler' => $handler,
        ];
    }

    public function dispatch(string $method, string $uri)
    {
        $method = strtoupper($method);
        $uri = parse_url($uri, PHP_URL_PATH); // чтобы убрать query string
        error_log("URI: $uri, METHOD: $method");

        foreach ($this->routes as $route) {
            if ($route->method !== $method) {
                continue;
            }

            $params = $this->matchUri($route->uri, $uri);
            if ($params !== false) {
                // Если handler — массив [объект, метод]
                if (is_array($route->handler)) {
                    [$controller, $methodName] = $route->handler;
                    return $controller->$methodName($params);
                }
                // Если handler — callable
                return call_user_func($route->handler, $params);
            }
        }

        // 404
        http_response_code(404);
        echo "404 Not Found";
        exit;
    }

    private function matchUri(string $routeUri, string $requestUri): array|false
    {
        $routeParts = explode('/', trim($routeUri, '/'));
        $requestParts = explode('/', trim($requestUri, '/'));

        if (count($routeParts) !== count($requestParts)) {
            return false;
        }

        $params = [];

        foreach ($routeParts as $i => $part) {
            if (str_starts_with($part, '{') && str_ends_with($part, '}')) {
                $key = trim($part, '{}');
                $params[$key] = $requestParts[$i];
            } elseif ($part !== $requestParts[$i]) {
                return false;
            }
        }

        return $params;
    }
}
