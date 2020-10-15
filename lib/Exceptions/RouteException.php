<?php

namespace Lib\Exceptions;

use Lib\Exceptions\GeralException;

class RouteException extends GeralException
{
    /**
     * Armazena a view do Controller.
     * 
     * @var string
     */
    public $view = 'routeException';

    /**
     * Armazena o Controller.
     * 
     * @var string
     */
    public $controller = \Lib\Exceptions\Controllers\RouteController::class;

}
