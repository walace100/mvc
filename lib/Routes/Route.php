<?php

namespace Lib\Routes;

final class Route
{
    private static $url;

    private static $uri;

    private static $routes = [];

    private static function bootstrap(): void
    {
        self::setURL();
        self::getConstants();
        self::setURI();
    }

    public static function get(string $route, $action, string $method = null): void
    {
        self::routes($route, 'GET', $action, $method);
    }

    public static function post(string $route, $action, string $method): void
    {
        self::routes($route, 'POST', $action, $method);
    }

    private static function setURI(): void
    {
        $uri = \str_replace(APP_BASE, '', self::$url);
        self::$uri = $uri;
    }

    private static function routes(string $route, string $method, $action, string $function = null): void
    {
        self::bootstrap();
        array_push(self::$routes, [
            'route' => $route,
            'method' => $method,
            'action' => $action,
            'function' => $function
        ]);
    }

    private static function verifyRoute(): array
    {
        foreach (self::$routes as $route) {
            if ($route['route'] === self::$uri) {
                return $route;
            } 
        }
        return [];
    }

    public static function callController(array $route)
    {
        if (!is_string($route['action'])) {
            \call_user_func($route['action']);
        } elseif (isset($route['function'])) {
            $class = '\Controllers\\' . $route['action'];
            $method = $route['function'];
            (new $class)->$method();
        }
    }

    public static function run(): void
    {
        $route = self::verifyRoute();
        if($route) {
           self::callController($route);
        } else {
            echo '404';
        }
    }

    private static function setURL(): void
    {
        self::$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    private static function getConstants(): void
    {
        $root = dirname(__DIR__, 2);
        require_once $root . '/config.php';
    }

}