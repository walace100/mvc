<?php

namespace Lib\Http;

use Lib\Http\Request;
use ReflectionFunction;

final class CallController
{
    private $arrURI;

    public function __construct(Route2 $route, array $arrURI)#
    {
        $this->arrURI = $arrURI;
        $this->index($route);
    }

    private function index(Route2 $route): void #
    {
        $param = [];
        if ($route->parameters->variable) {
            $param = $this->getParameters($route->parameters->routes, $this->arrURI);
        } 
        if ($route->closure) {
            $closure = $this->positionParameters($route->action);
            if (!is_null($closure)) {
                array_splice($param, $closure, 0, [new Request($route->method)]);
            }
        }
        $this->callController($route, $param);
    }

    private function getParameters(array $parameters, array $arrURI): array
    {
        $param = [];
        $keys = \array_keys($parameters);

        foreach ($keys as $value) {
           if (\preg_match('/(?<![\w+])([1-9]+)$/', $arrURI[$value])) {
                \array_push($param, (int) $arrURI[$value]);
           } else {
                \array_push($param, $arrURI[$value]);
           }
        }
        return $param;
    }

    private function positionParameters(object $closure): ?int
    {
        $arrayClosure = (new ReflectionFunction($closure))->getParameters();
        $index = array_keys($arrayClosure);

        foreach ($arrayClosure as $i => $array) {
            if ($array->getType() && $array->getType()->getName() == 'Lib\Http\Request') {
                return $index[$i];
            }
        }
        return null;
    }

    private function callController(Route2 $route, array $parameters): void
    {
        if ($route->closure) {
            \call_user_func($route->action, ...$parameters);
        } elseif ($route->function) {
            $class = '\Controllers\\' . $route->action;
            $method = $route->function;
            (new $class)->$method(...$parameters);
        }
    }
} 
