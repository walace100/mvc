<?php

namespace Lib\Autoload;

final class AutoloadReal
{

    private static $level = 2;

    public static function bootstrap(): void
    {
        $root = \dirname(__DIR__, self::$level);
        if (file_exists($root . '/vendor/autoload.php')) {
            self::composerAutoload();
        } else {
            self::libAutoload();
        }
    }

    private static function composerAutoload(): void
    {
        $root = \dirname(__DIR__, self::$level);
        self::require($root . '/vendor/autoload.php');
    }

    private static function libAutoload(): void
    {
        \spl_autoload_register('self::autoload');
    }

    private static function autoload($class): void
    {
        $namespace = \str_replace('\\', '/', $class);
        $finalClass = \str_replace('Lib', 'lib', $class);
        $root = \dirname(__DIR__, self::$level);
        self::require($root . '/' . $finalClass . '.php');
    }

    private static function require($path): void
    {
        require_once $path;
    }
}