<?php

namespace Lib\Exceptions;

use Exception;

class GeralException extends Exception
{
    public $view = 'geralException';

    public $controller = \Lib\Exceptions\Controllers\ExceptionController::class;

}
