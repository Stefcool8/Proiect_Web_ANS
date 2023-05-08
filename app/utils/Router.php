<?php

namespace App\Utils;

class Router {
    private array $routes = [];

    public function add(string $method, string $pattern, callable $callback): void {
        $pattern = $this->convertPattern($pattern);
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'callback' => $callback
        ];
    }

    private function convertPattern(string $pattern): string {
        $pattern = preg_replace('/{(\w+)}/', '(?P<$1>[^/]+)', $pattern);
        return "@^{$pattern}$@D";
    }

    public function dispatch(string $method, string $uri): void {
        $method = strtoupper($method);
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        foreach ($this->routes as $route) {
            if ($route['method'] == $method && preg_match($route['pattern'], $uri, $matches)) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                }
                call_user_func_array($route['callback'], $this->processMatches($matches));
                return;
            }
        }

        // Route not found, return 404
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }

    private function processMatches(array $matches): array {
        $args = [];
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $args[$key] = $value;
            }
        }
        return $args;
    }
}
