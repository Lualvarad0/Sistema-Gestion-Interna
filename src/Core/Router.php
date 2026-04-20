<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array  $routes   = [];
    private string $basePath;

    public function __construct(string $basePath = '')
    {
        $this->basePath = rtrim($basePath, '/');
    }

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = $_SERVER['REQUEST_URI'];

        // Quitar query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        // Quitar el basePath y normalizar
        $path = '/' . ltrim(substr(rawurldecode($uri), strlen($this->basePath)), '/');
        $path = ($path !== '/') ? rtrim($path, '/') : '/';

        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route => $handler) {
            $pattern = '#^' . preg_replace('/\{[^}]+\}/', '([^/]+)', preg_quote($route, '#')) . '$#';

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                [$class, $action] = $handler;
                (new $class())->$action(...$matches);
                return;
            }
        }

        $this->notFound();
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>404</title>'
            . '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>'
            . '<body class="d-flex align-items-center justify-content-center min-vh-100 bg-light">'
            . '<div class="text-center"><h1 class="display-1 fw-bold text-muted">404</h1>'
            . '<p class="lead">Página no encontrada.</p>'
            . '<a href="' . BASE_URL . '/" class="btn btn-primary">Volver al inicio</a>'
            . '</div></body></html>';
    }
}
