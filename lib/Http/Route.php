<?php

namespace Lib\Http;

use stdClass;

final class Route
{
    /**
     * Armazena o nome da rota.
     * 
     * @var string
     */
    public $route;

    /**
     * Armazena o método da rota.
     * 
     * @var string
     */
    public $method;

    /**
     * Armazena a ação da rota.
     * 
     * @var mixed
     */
    public $action;

    /**
     * Armazena o método da classe da rota.
     * 
     * @var string|null
     */
    public $function;

    /**
     * Armazena os parâmetros da rota.
     * 
     * @var \stdClass
     */
    public $parameters;

    /**
     * Armazena a rota dividida em array.
     * 
     * @var array
     */
    public $arrRoutes;

    /**
     * Armazena se o parâmetros $action é uma callback.
     * 
     * @var bool
     */
    public $closure;

    /**
     * Define o método.
     * 
     * Define a ação da rota.
     * 
     * Define a método da classe da rota.
     * 
     * Define a rota.
     * 
     * Define os parâmetros da rota.
     * 
     * Define a rota em array.
     * 
     * Define se a $action é uma closure.
     * 
     * @param  string  $route
     * @param  string  $method
     * @param  mixed  $action
     * @param  string|null  $function
     * @return void
     */
    public function __construct(string $route, string $method, $action, ?string $function = null)
    {
        $this->method = $method;
        $this->action = $action;
        $this->function = $function;
        $this->route = $this->cleanRoute($route);
        $this->parameters = $this->parameters($route);
        $this->arrRoutes = $this->arrRoutes($this->route);
        $this->closure = !\is_string($action);
    }

    /**
     * Limpa as rotas tirando / do final e colocando / no começo se não tiver.
     * 
     * @param  string  $route
     * @return string
     */
    private function cleanRoute(string $route): string
    {
        if (\preg_match('/\/$/', $route) && \strlen($route) > 1) {
            $route = \substr($route, 0, \strlen($route) -1);
        }
        if (!preg_match('/^\//', $route) && \strlen($route) > 1) {
            $route = '/' . $route;
        }
        return $route;
    }

    /**
     * Pega os parâmetros da rota.
     * 
     * @param  string  $route
     * @return \stdClass
     */
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

    /**
     * Retorna as rotas dividida por /.
     * 
     * @param  string  $routes
     * @return array
     */
    private function arrRoutes(string $routes): array
    {
        return \explode('/', $routes);
    }
}
