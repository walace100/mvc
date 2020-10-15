<?php

namespace Lib\Exceptions;

use Lib\Exceptions\GeralException;

class ControllerException extends GeralException
{
    /**
     * Armazena a view do Controller.
     * 
     * @var string
     */
    public $view = 'controllerException';

    /**
     * Armazena o Controller.
     * 
     * @var string
     */
    public $controller = \Lib\Exceptions\Controllers\ViewController::class;

}
