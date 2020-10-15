<?php

namespace Lib\Http;

use Lib\Http\Request;
use ReflectionFunction;
use Lib\Exceptions\RouteException;

final class CallController
{
    /**
     * Armazena a URI em array.
     * 
     * @var array
     */
    private $arrURI;

    /**
     * Define a URI e chama o método index.
     * 
     * @param  \Lib\Http\Route  $route
     * @param  array  $arrURI
     * @return void
     */
    public function __construct(Route $route, array $arrURI)
    {
        $this->arrURI = $arrURI;
        $this->index($route);
    }

    /**
     * Define os parâmetros do callback ou do método e chama o CallController.
     * 
     * @param  \Lib\Http\Route  $route
     * @return void
     */
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

    /**
     * Pega os parâmetros da URI.
     * 
     * @param  \Lib\Http\Route  $route
     * @param  array  arrURI
    */
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

    /**
     * Retorna a posição do \Lib\Http\Request no callback ou do método.
     * 
     * @param  object  $closure
     * @return int|null
     */
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

    /**
     * Chama a classe ou o callback.
     * 
     * @param  \Lib\Http\Route  $route
     * @param  array  $parameters
     * @return void
     * 
     * @throws \Lib\Http\Exception\RouteException
     */
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
            throw new RouteException('Ocorreu um erro: ' . $e->getMessage());
        }
    }
} 
