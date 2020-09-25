<?php

namespace Lib\Http;

use Lib\Http\VerifyRoute;
use Lib\Http\CallController;

final class ControllerRoute
{
    private $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
        $this->index();
    }

    private function index(): void
    {
        $verify = new VerifyRoute($this->routes);
        
        if ($verify->code === 200) {
            new CallController($verify->callRoute, $verify->arrURI);
        } else {
            header('HTTP/1.1 ' . $verify->code);
            echo $verify->code;
        }
    }
}
