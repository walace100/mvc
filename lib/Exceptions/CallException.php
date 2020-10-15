<?php

namespace Lib\Exceptions;

use Exceptions\ConfigException;

final class CallException
{
    /**
     * Instancia os Controllers das Exceptions
     * 
     * @param  mixed  $exception
     * @return void
     */
    public function renderView($exception): void
    {
        $params = [
            'getMessage' => $exception->getMessage(),
            'getCode' => $exception->getCode(),
            'getFile' => $exception->getFile(),
            'getLine' => $exception->getLine(),
            'getTrace' => $exception->getTrace(),
            'getPrevious' => $exception->getPrevious(),
            'getTraceAsString' => $exception->getTraceAsString()
        ];

        $config = new ConfigException();
        $exceptions = $config::EXCEPTIONS;

        $controller = \Lib\Exceptions\Controllers\ExceptionController::class;
        $view = 'geralException';

        foreach ($exceptions as $except) {

            if ($except === get_class($exception)) {

                $exceptionClass = new $except();
                $controller = $exceptionClass->controller;
                $view = $exceptionClass->view;

            }
        }

        new $controller($view, $params);
    }
}
