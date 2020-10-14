<?php

namespace Lib\Exceptions;

use Lib\Exceptions\GeralException;

class ControllerException extends GeralException
{
    public $view = 'controllerException';

    public $controller = \Lib\Exceptions\Controllers\ViewController::class;

}
