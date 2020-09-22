<?php

namespace Lib\Http;

final class VerifyRoute
{
    private $routes;

    public function __construct($routes)
    {
        $this->routes = $routes;
    }
}
