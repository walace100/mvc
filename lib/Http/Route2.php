<?php

namespace Lib\Http;

use stdClass;

final class Route2
{
    public $route;

    public $rawRoute;

    public $method;

    public $action;

    public $function;

    public $parameters;

    public function __construct(string $route, string $method, $action, string $function = null)
    {
        $this->rawRoute = $route;
        $this->method = $method;
        $this->action = $action;
        $this->function = $function;
        $this->route = $this->cleanRoute($route);
        $this->parameters = $this->parameters($route);
    }

    private function cleanRoute($route): string
    {
        if (\preg_match('/\/$/', $route) && \strlen($route) > 1) {
            $route = \substr($route, 0, \strlen($route) -1);
        }
        if (!preg_match('/^\//', $route) && \strlen($route) > 1) {
            $route = '/' . $route;
        }
        return $route;
    }

    private function parameters(string $route): stdClass
    {
        $arrRoute = \explode('/', $route);
        $isVal = \preg_match_all('/({)(?<={)\w+(})/', $route);

        $response = new stdClass();
        $response->count = 0;
        $response->variable = false;
        $response->routes = [];

        if ($isVal) {
            $routesVal = \preg_grep('/({)(?<={)\w+(})/', $arrRoute);

            $response->count = \count($routesVal);
            $response->variable = true;
            $response->routes = $routesVal;

            return $response;
        } else {
            return $response;
        }
    }
}
