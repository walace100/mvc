<?php

namespace Lib\Exceptions;

use Exception;

class GeralException extends Exception
{
    protected $view = 'geralException';

    protected $namespaceController = \Lib\Exceptions\ExceptionController::class;

    final protected function renderView()
    {
        $params = [
            'getMessage' => $this->getMessage(),
            'getCode' => $this->getCode(),
            'getFile' => $this->getFile(),
            'getLine' => $this->getLine(),
            'getTrace' => $this->getTrace(),
            'getPrevious' => $this->getPrevious(),
            'getTraceAsString' => $this->getTraceAsString()
        ];
        new $this->namespaceController($this->view, $params);
    }

    final public function __destruct()
    {
        $this->renderView();
    }
}
