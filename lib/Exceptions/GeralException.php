<?php

namespace Lib\Exceptions;

use Exception;

class GeralException extends Exception
{
    /**
     * Armazena a view do Controller.
     * 
     * @var string
     */
    public $view = 'geralException';

    /**
     * Armazena o Controller.
     * 
     * @var string
     */
    public $controller = \Lib\Exceptions\Controllers\ExceptionController::class;

}
