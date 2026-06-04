<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];
    private Request $request;
    private Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get(string $path, array $callback, array $middlewares = []): void
    {
        $this->routes['GET'][$path] = [
            'callback' => $callback,
            'middlewares' => $middlewares
        ];
    }

    public function post(string $path, array $callback, array $middlewares = []): void
    {
        $this->routes['POST'][$path] = [
            'callback' => $callback,
            'middlewares' => $middlewares
        ];
    }

    public function resolve(): void
    {
        $method = $this->request->getMethod();
        $uri = $this->request->getUri();
        
        $route = $this->routes[$method][$uri] ?? null;
        
        if ($route === null) {
            $this->response->setStatusCode(404);
            $this->renderError(404);
            return;
        }
        
        $callback = $route['callback'];
        $middlewares = $route['middlewares'];
        
        foreach ($middlewares as $middlewareClass) {
            $middleware = new $middlewareClass();
            $middleware->execute($this->request, $this->response);
        }
        
        list($controllerClass, $action) = $callback;
        $controller = new $controllerClass();
        $controller->$action($this->request, $this->response);
    }

    private function renderError(int $code): void
    {
        $viewPath = dirname(__DIR__, 2) . "/views/errors/{$code}.php";
        $layoutPath = dirname(__DIR__, 2) . "/views/layouts/app.php";
        
        $title = "{$code} - Page Not Found";
        $baseUrl = \App\Config\Env::get('APP_URL', 'http://localhost/Login-Google');
        
        if (file_exists($viewPath) && file_exists($layoutPath)) {
            ob_start();
            require $viewPath;
            $content = ob_get_clean();
            require $layoutPath;
        } else {
            echo "<h1>Error {$code}</h1>";
        }
    }
}
