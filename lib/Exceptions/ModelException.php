<?php

namespace Lib\Exceptions;

use Lib\Exceptions\GeralException;

class ModelException extends GeralException
{
    /**
     * Armazena a view do Controller.
     * 
     * @var string
     */
    public $view = 'modelException';

    /**
     * Armazena o Controller.
     * 
     * @var string
     */
    public $controller = \Lib\Exceptions\Controllers\ModelController::class;

}
