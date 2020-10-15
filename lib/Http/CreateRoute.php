<?php

namespace Lib\Http;

use Lib\Http\ControllerRoute;
use Lib\Http\Route;
use Lib\Exceptions\RouteException;

final class CreateRoute
{
    /**
     * Todas as rotas serão armazenadas aqui.
     * 
     * @var array
     */
    static private $routes = [];

    /**
     * Define o método GET para as rotas.
     * 
     * @param  string  $route
     * @param  mixed  $action
     * @param  string|null  $method
     * @return void
     * 
     * @throws \Lib\Exceptions\RouteException
     */
    public static function get(string $route, $action, ?string $method = null): void
    {
        if (gettype($action) !== 'string' && gettype($action) !== 'object') {
            throw new RouteException('Argumento passado: $action não é string ou object');
        }

        self::routes($route, 'GET', $action, $method);
    }

    /**
     * Define o método POST para as rotas.
     * 
     * @param  string  $route
     * @param  mixed  $action
     * @param  string|null  $method
     * @return void
     * 
     * @throws \Lib\Exceptions\RouteException
     */
    public static function post(string $route, $action, ?string $method = null): void
    {
        if (gettype($action) !== 'string' && gettype($action) !== 'object') {
            throw new RouteException('Argumento passado: $action não é string ou object');
        }

        self::routes($route, 'POST', $action, $method);
    }

    /**
     * Define o método ANY para as rotas.
     * 
     * @param  string  $route
     * @param  mixed  $action
     * @param  string|null  $method
     * @return void
     * 
     * @throws \Lib\Exceptions\RouteException
     */
    public static function any(string $route, $action, ?string $method = null): void
    {
        if (gettype($action) !== 'string' && gettype($action) !== 'object') {
            throw new RouteException('Argumento passado: $action não é string ou object');
        }

        self::routes($route, 'ANY', $action, $method);
    }

    /**
     * Registra as rotas na classe \Lib\Http\Route e insere em atributo $routes.
     * 
     * @param  string  $route
     * @param  string  $method
     * @param  mixed  $action
     * @param  string|null  $function
     * @return void
     */
    private static function routes(string $route, string $method, $action, string $function = null): void
    {
        $routeClass = new Route($route, $method, $action, $function);
        array_push(self::$routes, $routeClass);
    }

    /**
     * Retorna o domínio concatenado com a rota passada pelo parâmetro.
     * 
     * @param  string  $route
     * @return string
     */
    public static function string(string $route): string
    {
        if (preg_match('/^\//', $route)) {
            return $_SERVER['REQUEST_SCHEME'] . '://' . APPBASE . $route;
        } else {
            $cleanRoute = '/' . $route;
            return $_SERVER['REQUEST_SCHEME'] . '://' . APPBASE . $cleanRoute;
        }
    }

    /**
     * Redireciona para a URL espeficicada.
     * 
     * @param  string  $route
     */
    public static function to(string $route): void
    {
        $url = self::string($route);
        \header('location: ' . $url);
    }

    /**
     * Após o registro de todas as rotas, inicia o Controller para gerenciar as rotas.
     * 
     * @return void
     */
    public static function run(): void
    {
        new ControllerRoute(self::$routes);
    }
}
