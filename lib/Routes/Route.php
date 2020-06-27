<?php

namespace Lib\Routes;

final class Route
{
    private static $url;

    private static $uri;

    private static $routes = [];

    private static $arrURI;

    private static function bootstrap(): void
    {
        self::setURL();
        self::getConstants();
        self::setURI();
        self::cleanRoutes();
        self::setArrURI();
    }

    public static function get(string $route, $action, string $method = null): void
    {
        self::routes($route, 'GET', $action, $method);
    }

    public static function post(string $route, $action, string $method): void
    {
        self::routes($route, 'POST', $action, $method);
    }

    private static function routes(string $route, string $method, $action, string $function = null): void
    {
        $routesVal = self::parameters($route);
        array_push(self::$routes, [
            'route' => $route,
            'method' => $method,
            'action' => $action,
            'function' => $function,
            'routesVal' => $routesVal
        ]);
    }

    private static function parameters(string $route): array
    {
        $arrRoute = \explode('/', $route);
        $isVal = preg_match_all('/({)(?<={)\w+(})/', $route);
        if ($isVal) {
            $routesVal = \preg_grep('/({)(?<={)\w+(})/', $arrRoute);
            return [
                'variable' => true,
                'routes' => $routesVal
            ];
        } else {
            return [
                'variable' => false,
                'routes' => []
            ];
        }
    }  

    public static function run(): void
    {
        self::bootstrap();
        $route = self::verifyRoute();
        if($route) {
           self::callController($route);
        } else {
            echo '404';
        }
    }

    private static function verifyRoute()
    {
        foreach (self::$routes as $route) {
            if ($route['route'] === self::$uri) {
                return $route;
            } elseif ($route['routesVal']['variable']) {
                $arrRoutes = \explode('/', $route['route']);
                if (count($arrRoutes) === count(self::$arrURI)) {
                    $count = 0;
                    foreach (self::$arrURI as $key => $uri) {
                        if($uri === $arrRoutes[$key] || \preg_match('/{/', $arrRoutes[$key])) {
                            $count++;
                        }
                    }
                    if ($count === count(self::$arrURI)) {
                        return $route;
                    }
                }
            }
        } 
        return [];
    }

    private static function callController(array $route): void
    {
        if (!is_string($route['action'])) {
            \call_user_func($route['action']);
        } elseif (isset($route['function'])) {
            $class = '\Controllers\\' . $route['action'];
            $method = $route['function'];
            (new $class)->$method();
        }
    }

    private static function cleanRoutes(): void
    {
        if (\preg_match('/\/$/', self::$uri) && \strlen(self::$uri) > 1) {
            self::$uri = \substr(self::$uri, 0, \strlen(self::$uri) -1);
        }
        foreach (self::$routes as $key => $routes) {
            if (\preg_match('/\/$/', $routes['route']) && \strlen($routes['route']) > 1) {
                $routes['route'] = \substr($routes['route'], 0, \strlen($routes['route']) -1);
                self::$routes[$key]['route'] = $routes['route'];
            }
            if (!preg_match('/^\//', $routes['route']) && \strlen($routes['route']) > 1) {
                $routes['route'] = '/'. $routes['route'];
                self::$routes[$key]['route'] = $routes['route'];
            }
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

    private static function setURI(): void
    {
        $uri = \str_replace(APP_BASE, '', self::$url);
        $uriFinal = \preg_replace('/\?.*/', '', $uri);
        self::$uri = $uriFinal;
    }

    private static function setArrURI()
    {
        self::$arrURI = \explode('/', self::$uri);
    }
}
