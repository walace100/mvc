<?php

namespace Lib\Exceptions;

use Lib\Exceptions\GeralException;

class RouteException extends GeralException
{
    public $view = 'routeException';

    public $controller = \Lib\Exceptions\Controllers\RouteController::class;

}
