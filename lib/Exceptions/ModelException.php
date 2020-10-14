<?php

namespace Lib\Exceptions;

use Lib\Exceptions\GeralException;

class ModelException extends GeralException
{
    public $view = 'modelException';

    public $controller = \Lib\Exceptions\Controllers\ModelController::class;

}
