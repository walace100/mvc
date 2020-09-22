<?php

namespace Lib\Autoload;

final class AutoloadReal
{
    public static function bootstrap(): void
    {
        if (file_exists(ROOT . '/vendor/autoload.php')) {
            self::composerAutoload();
        } else {
            self::libAutoload();
        }
    }

    private static function composerAutoload(): void
    {
        self::require(ROOT . '/vendor/autoload.php');
    }

    private static function libAutoload(): void
    {
        \spl_autoload_register('self::autoload');
    }

    private static function autoload($class): void
    {
        $namespace = \str_replace('\\', '/', $class);
        $finalClass = \str_replace('Lib', 'lib', $class);
        self::require(ROOT . '/' . $finalClass . '.php');
    }

    private static function require($path): void
    {
        require_once $path;
    }
}