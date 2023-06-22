<?php

namespace utils;

use App\utils\Router;
use PHPUnit\Framework\TestCase;
use App\controllers\UserController;
use ReflectionClass;
use ReflectionException;

class RouterTest extends TestCase
{
    public function testGetRouter_ReturnsInstanceOfRouter()
    {
        // Arrange
        $expected = Router::class;

        // Act
        /** @var Router|mixed $actual */
        $actual = Router::getRouter();

        // Assert
        $this->assertInstanceOf($expected, $actual);
    }

    public function testAddRoute()
    {
        // Arrange
        $router = Router::getRouter();
        $router->add('GET', 'api/user/{uuid}', 'UserController::get');

        /** @var string|mixed $regex */
        $regex = '@^api/user/(?P<uuid>[^/]+)$@D';
        /** @var string|mixed $methodUppercase */
        $methodUppercase = 'GET';
        /** @var string|mixed $methodLowercase */
        $methodLowercase = 'get';

        // Act
        $routes = $this->getPropertyValue($router);
        $route = $routes[0];

        // Assert
        $this->assertCount(1, $routes);
        $this->assertEquals($methodUppercase, $route['method']);
        $this->assertEquals($regex, $route['pattern']);
        $this->assertInstanceOf(UserController::class, $route['callback'][0]);
        $this->assertEquals($methodLowercase, $route['callback'][1]);
    }

    public function testDispatchMatchingRoute()
    {
        $router = Router::getRouter();
        $router->add('GET', '/api/home', 'HomeController::get');

        $this->expectOutputString('{"status_code":200,"data":{"title":"Open source tool for data visualization"}}');

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/api/home';

        $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    }

    public function testDispatchNoMatchingRoute()
    {
        $router = Router::getRouter();
        $router->add('GET', '/api/about', 'AboutController::get');

        // Set the request method and URI
        $method = 'POST';
        $uri = '/api/home';

        // Capture the output
        ob_start();
        $router->dispatch($method, $uri);
        ob_get_clean();

        /** @var int|mixed $expectedStatusCode */
        $expectedStatusCode = 302;
        /** @var int|mixed $actualStatusCode */
        $actualStatusCode = http_response_code();

        // Check the response status code and output
        $this->assertEquals($expectedStatusCode, $actualStatusCode);
    }

    private function getPropertyValue($object)
    {
        try {
            $reflector = new ReflectionClass($object);
            $property = $reflector->getProperty('routes');
            $property->setAccessible(true);

            return $property->getValue($object);
        } catch (ReflectionException $e) {
            echo $e->getMessage();
        }

        return null;
    }
}
