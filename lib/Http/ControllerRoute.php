<?php

namespace Lib\Http;

use Lib\Http\VerifyRoute;
use Lib\Http\CallController;

final class ControllerRoute
{
    /**
     * Registra as rotas.
     * 
     * @var array
     */
    private $routes;

    /**
     * Armazena as rotas e chama o método index.
     * 
     * @param  string  $routes
     * @return void
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
        $this->index();
    }

    /**
     * Verifica a rota certa e chama a classe \Lib\Http\CallController
     * 
     * @return void
     */
    private function index(): void
    {
        $verify = new VerifyRoute($this->routes);
        
        if ($verify->code === 200) {
            new CallController($verify->callRoute, $verify->arrURI);
        } else {
            header('HTTP/1.1 ' . $verify->code);
            echo '<h1>' . $verify->code . '</h1>';
        }
    }
}
