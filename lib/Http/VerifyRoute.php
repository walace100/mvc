<?php

namespace Lib\Http;

final class VerifyRoute
{
    private $routes;

    private $URL;

    private $URI;

    public $arrURI;

    public $code;

    public $callRoute;

    public function __construct(array $routes)
    {
        $this->routes = $this->orderRoutes($routes);
        $this->URL = $this->setURL();
        $uri = $this->setURI($this->URL);
        $this->URI = $this->cleanURI($uri);
        $this->arrURI = $this->setArrURI($this->URI);
        $this->callRoute = $this->verifyRoute($this->routes);
    }

    private function verifyRoute(array $routes): ?Route2 # ###
    {
        $wrongmethod = false;

        foreach ($routes as $route) {

            if ($route->route === $this->URI) {
                if ($route->method === $_SERVER['REQUEST_METHOD'] || $route->method === 'ANY') {
                    $this->code = 200;
                    return $route;
                } else {
                    $wrongmethod = true;
                }

            } elseif ($route->parameters->variable && \count($route->arrRoutes) === \count($this->arrURI)) {

                $count = 0;

                foreach ($this->arrURI as $key => $uri) {
                    if ($uri === $route->arrRoutes[$key] || \preg_match('/{/', $route->arrRoutes[$key])) {
                        $count++;
                    }
                }

                if ($count === \count($this->arrURI)) {

                    if ($route->method === $_SERVER['REQUEST_METHOD'] || $route->method === 'ANY') {
                        $this->code = 200;
                        return $route;
                    } else {
                        $wrongmethod = true;
                    }

                }
            }
        }

        if ($wrongmethod) {
            $this->code = 405;
        } else {
            $this->code = 404;
        }
        return null;
    }

    private function orderRoutes(array $routes): array
    {
        $maxNum = [0];

        foreach ($routes as $route) {
            if (!in_array($route->parameters->count, $maxNum)) {
                \array_push($maxNum, $route->parameters->count);
            }
        }
        \sort($maxNum);

        foreach ($maxNum as $num) {
            $routeFilters = array_filter($routes, function ($value) use ($num) {
                return $value->parameters->count === $num;
            });

            foreach ($routeFilters as $routeFilter) {
                $routeFinal[] = $routeFilter;
            }
        }
        return $routeFinal;
    }

    private function setURL(): string
    {
        $dominio = \preg_split('/^www\./', $_SERVER['HTTP_HOST'])[1] ?? $_SERVER['HTTP_HOST'];
        return $dominio . $_SERVER['REQUEST_URI'];
    }

    private function setURI(string $url): string
    {
        $uri = \str_replace(APPBASE, '', $url);
        $uriFinal = \preg_replace('/\?.*/', '', $uri);
        return $uriFinal;
    }

    private function cleanURI(string $uri): string
    {
        if (\preg_match('/\/$/', $uri) && \strlen($uri) > 1) {
            return \substr($uri, 0, \strlen($uri) -1);
        }
        return $uri;
    }

    private function setArrURI(string $uri): array
    {
        return \explode('/', $uri);
    }
}
