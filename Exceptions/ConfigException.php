<?php

namespace Exceptions;

final class ConfigException
{
    public const EXCEPTIONS = [
        \Lib\Exceptions\GeralException::class,
        \Lib\Exceptions\RouteException::class,
        \Lib\Exceptions\ControllerException::class,
    ];
}
