<?php

namespace Lib\Http;

use Lib\Http\VerifyRoute;

final class ControllerRoute
{
    private $routes;

    public function __construct($routes)
    {
        $this->routes = $routes;
        $this->index();
    }

    private function index()
    {
        $verify = new VerifyRoute($this->routes);
        
        if ($verify->code === 200) {
            var_dump('<pre>', $verify->callRoute);
        } else {
            header('HTTP/1.1 ' . $verify->code);
            echo $verify->code;
        }
    }
}
