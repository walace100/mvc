<?php

namespace Exceptions;

final class ConfigException
{
    /**
     * Aqui você registra suas Exceptions.
     * 
     * Se a exception for criada, mas não registrada
     * retornará uma exception padrão: \Lib\Exceptions\GeralException
     * 
     * @var array
     */
    public const EXCEPTIONS = [
        \Lib\Exceptions\GeralException::class,
        \Lib\Exceptions\RouteException::class,
        \Lib\Exceptions\ControllerException::class,
        \Lib\Exceptions\ModelController::class,
    ];
}
