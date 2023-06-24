<?php

namespace App\utils;

/**
 * Class Router
 * Handles routing for the application.
 *
 * @package App\Utils
 */
class Router {
    // default controller namespace locations
    private array $locations = [
        'default' => 'App\Controllers\\'
    ];
    // singleton instance of the Router class
    private static ?Router $router = null;
    // array of routes
    private array $routes = [];

    // private constructor to prevent creating instances from outside
    private function __construct() {
    }

    /**
     * Get the singleton instance of the Router class.
     *
     * @return Router The singleton instance of the Router class.
     */
    public static function getRouter(): Router {
        if (self::$router === null) {
            self::$router = new Router();
        }
        return self::$router;
    }

    /**
     * Add a route.
     *
     * @param string $method The HTTP method (e.g., 'GET', 'POST').
     * @param string $pattern The route pattern (e.g., '/users/{id}').
     * @param string $callback The callback (e.g., 'UserController::get').
     * @param string $location The location of the controller (defaults to 'default').
     */
    public function add(string $method, string $pattern, string $callback, string $location = 'default'): void {
        // convert the pattern into a regular expression
        $pattern = $this->convertPattern($pattern);

        // convert the callback string to a callable
        $callback = $this->convertStringToCallback($callback, $location);

        // add the route to the routes array
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'callback' => $callback
        ];
    }

    /**
     * Dispatch the request to the appropriate route.
     *
     * @param string $method The HTTP method of the request.
     * @param string $uri The request URI.
     */
    public function dispatch(string $method, string $uri): void {
        $method = strtoupper($method);

        // check if the request is an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        // iterate through the routes and find a match
        foreach ($this->routes as $route) {
            if ($route['method'] == $method && preg_match($route['pattern'], $uri, $matches)) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                }

                // call the callback with the matched parameters
                call_user_func_array($route['callback'], $this->processMatches($matches));
                return;
            }
        }

        // no matching route found, return a 404 status
        header("HTTP/1.1 404 Not Found");
        header("Location: /404");
    }

    /**
     * Extract named parameters from the matches array.
     *
     * @param array $matches The matches array from preg_match().
     * @return array The array of named parameters.
     */
    private function processMatches(array $matches): array {
        $args = [];
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $args[$key] = $value;
            }
        }
        return $args;
    }

    /**
    * Convert a callback string into a callable.
    * 
    * @param string $callbackString The callback string, e.g. "HomeController::get"
    * @param string $location The location of the controller.
    * 
    * @return callable The callable
    */
    private function convertStringToCallback(string $callbackString, string $location): callable {
        // append the controller namespace to the callback string
        $callbackString = $this->locations[$location] . $callbackString;

        // split the callback string into class and method names
        list($className, $methodName) = explode('::', $callbackString);

        // return the callable as an array with an instance of the class and method name
        return [new $className, $methodName];
    }

    /**
     * Convert a route pattern into a regular expression pattern for URL matching.
     * Replaces placeholders (enclosed in {}) with named regular expression groups.
     *
     * @param string $pattern The route pattern, e.g. "/users/{id}"
     * 
     * @return string The converted regular expression pattern, e.g. "@^/users/(?P<id>[^/]+)$@D"
     */
    private function convertPattern(string $pattern): string {
        // replace placeholders with named regular expression groups
        $pattern = preg_replace('/{(\w+)}/', '(?P<$1>[^/]+)', $pattern);

        // return the converted regular expression pattern
        return "@^$pattern$@D";
    }

}