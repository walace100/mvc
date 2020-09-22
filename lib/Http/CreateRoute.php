<?php

namespace Lib\Http;

use Lib\Http\Route2;

final class CreateRoute
{
    static private $routes = [];

    public static function get(string $route, $action, string $method = null): void
    {
        self::routes($route, 'GET', $action, $method);
    }

    public static function post(string $route, $action, string $method = null): void
    {
        self::routes($route, 'POST', $action, $method);
    }

    public static function any(string $route, $action, string $method = null): void
    {
        self::routes($route, 'ANY', $action, $method);
    }

    private static function routes(string $route, string $method, $action, string $function = null): void
    {
        $routeClass = new Route2($route, $method, $action, $function);
        array_push(self::$routes, $routeClass);
    }

    public static function run()
    {
        new VerifyRoute(self::$routes);
    }
}
