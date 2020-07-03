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
        self::cleanURI();
        self::setArrURI();
    }

    public static function get(string $route, $action, string $method = null): void
    {
        self::routes($route, 'GET', $action, $method);
    }

    public static function post(string $route, $action, string $method = null): void
    {
        self::routes($route, 'POST', $action, $method);
    }

    private static function routes(string $route, string $method, $action, string $function = null): void
    {
        $cleanRoute = self::cleanRoute($route);
        $routesVal = self::parameters($cleanRoute);
        \array_push(self::$routes, [
            'route' => $cleanRoute,
            'method' => $method,
            'action' => $action,
            'function' => $function,
            'routesVal' => $routesVal
        ]);
    }

    private static function cleanRoute(string $route): string
    {
        if (\preg_match('/\/$/', $route) && \strlen($route) > 1) {
            $route = \substr($route, 0, \strlen($route) -1);
        }
        if (!preg_match('/^\//', $route) && \strlen($route) > 1) {
            $route = '/'. $route;
        }
        return $route;
    }

    private static function parameters(string $route): array
    {
        $arrRoute = \explode('/', $route);
        $isVal = \preg_match_all('/({)(?<={)\w+(})/', $route);
        if ($isVal) {
            $routesVal = \preg_grep('/({)(?<={)\w+(})/', $arrRoute);
            $keys = \array_keys($routesVal);
            return [
                'variable' => true,
                'keys' => $routesVal
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
        if (!isset($route['response'])) {
           self::callController($route);
        } else {
            header('HTTP/1.1 ' . $route['response']);
            echo $route['response'];
        }
    }

    private static function verifyRoute(): array
    {
        foreach (self::$routes as $route) {
            $arrRoutes = \explode('/', $route['route']);
            if ($route['route'] === self::$uri) {
                if ($route['method'] === $_SERVER['REQUEST_METHOD']) {
                    return $route;
                }
                return [
                    'response' => 405
                ];
            } elseif ($route['routesVal']['variable'] && \count($arrRoutes) === \count(self::$arrURI)) {
                $count = 0;
                foreach (self::$arrURI as $key => $uri) {
                    if ($uri === $arrRoutes[$key] || \preg_match('/{/', $arrRoutes[$key])) {
                        $count++;
                    }
                }
                if ($count === \count(self::$arrURI)) {
                    if ($route['method'] === $_SERVER['REQUEST_METHOD']) {
                        return $route;
                    } 
                    return [
                        'response' => 405
                    ];
                }
            }
        } 
        return [
            'response' => 404
        ];
    }

    private static function callController(array $route): void # tratar os parametros dos metodos e otimizar codigo
    {
        $param = [];
        if ($route['routesVal']['variable']) {
            $param = self::getParameters($route['routesVal']['keys']);
        }
        if (!is_string($route['action'])) {
            \call_user_func($route['action'], ...$param);
        } elseif ($route['function']) {
            $class = '\Controllers\\' . $route['action'];
            $method = $route['function'];
            (new $class)->$method(...$param);
        }
    }

    private static function getParameters(array $keys): array
    {
        $uri = self::$arrURI;
        $param = [];
        foreach ($keys as $i => $value) {
           if (\preg_match('/(?<![\w+])([1-9]+)$/', $uri[$i])) {
                \array_push($param, (int) $uri[$i]);
           } else {
                \array_push($param, $uri[$i]);
           }
        }
        return $param;
    }

    private static function setURL(): void
    {
        self::$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    private static function getConstants(): void
    {
        $root = \dirname(__DIR__, 2);
        require_once $root . '/config.php';
    }

    private static function setURI(): void
    {
        $uri = \str_replace(APP_BASE, '', self::$url);
        $uriFinal = \preg_replace('/\?.*/', '', $uri);
        self::$uri = $uriFinal;
    }

    private static function cleanURI(): void
    {
        if (\preg_match('/\/$/', self::$uri) && \strlen(self::$uri) > 1) {
            self::$uri = \substr(self::$uri, 0, \strlen(self::$uri) -1);
        }

    }

    private static function setArrURI(): void
    {
        self::$arrURI = \explode('/', self::$uri);
    }
}
