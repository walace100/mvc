<?php

namespace Lib\Http;

use Lib\Http\ControllerRoute;
use Lib\Http\Route;
use Lib\Exceptions\RouteException;

final class CreateRoute
{
    static private $routes = [];

    public static function get(string $route, $action, string $method = null): void
    {
        if (gettype($action) !== 'string' && gettype($action) !== 'object') {
            throw new RouteException('Argumento passado: $action não é string ou object');
        }

        self::routes($route, 'GET', $action, $method);
    }

    public static function post(string $route, $action, string $method = null): void
    {
        if (gettype($action) !== 'string' && gettype($action) !== 'object') {
            throw new RouteException('Argumento passado: $action não é string ou object');
        }

        self::routes($route, 'POST', $action, $method);
    }

    public static function any(string $route, $action, string $method = null): void
    {
        if (gettype($action) !== 'string' && gettype($action) !== 'object') {
            throw new RouteException('Argumento passado: $action não é string ou object');
        }

        self::routes($route, 'ANY', $action, $method);
    }

    private static function routes(string $route, string $method, $action, string $function = null): void
    {
        $routeClass = new Route($route, $method, $action, $function);
        array_push(self::$routes, $routeClass);
    }

    public static function string(string $route): string
    {
        if (preg_match('/^\//', $route)) {
            return $_SERVER['REQUEST_SCHEME'] . '://' . APPBASE . $route;
        } else {
            $cleanRoute = '/' . $route;
            return $_SERVER['REQUEST_SCHEME'] . '://' . APPBASE . $cleanRoute;
        }
    }

    public static function to(string $route): void
    {
        $url = self::string($route);
        \header('location: ' . $url);
    }

    public static function run(): void
    {
        new ControllerRoute(self::$routes);
    }
}
