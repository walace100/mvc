<?php

namespace Lib\Http;

use Lib\Http\Request;
use ReflectionFunction;
use Lib\Exceptions\RouteException;

final class CallController
{
    private $arrURI;

    public function __construct(Route $route, array $arrURI)
    {
        $this->arrURI = $arrURI;
        $this->index($route);
    }

    private function index(Route $route): void
    {
        $param = [];

        if ($route->parameters->variable) {
            $param = $this->getParameters($route, $this->arrURI);
        } 

        if ($route->closure) {
            $closure = $this->positionParameters($route->action);
            if (!is_null($closure)) {
                array_splice($param, $closure, 0, [new Request($route->method)]);
            }
        }
        $this->callController($route, $param);
    }

    private function getParameters(Route $route, array $arrURI): array
    {
        $param = [];

        foreach ($route->arrRoutes as $key => $value) {
            
            if (\preg_match('/({)(?<={)\w+(})/', $value)) {

                if (\preg_match('/(?<![\w+])([1-9]+)$/', $arrURI[$key])) {           
                    \array_push($param, (int) $arrURI[$key]);
                } else {
                    \array_push($param, $arrURI[$key]);
                }
                
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

    private function callController(Route $route, array $parameters): void
    {
        try {
            if ($route->closure) {
                \call_user_func($route->action, ...$parameters);
            } elseif ($route->function) {
                $class = '\Controllers\\' . $route->action;
                $instance = new $class();
                $method = $route->function;
                $instance->$method(...$parameters);
                $instance->run();
            }
        } catch (\Exception $e) {
            throw new RouteException('Classe ou função não existe');
        }
    }
} 
