<?php

namespace Lib\Autoload;

final class AutoloadReal
{

    private static $level = 2;

    public static function bootstrap(): void
    {
        self::setRoot();
        self::setAppBase();
        if (file_exists(ROOT . '/vendor/autoload.php')) {
            self::composerAutoload();
        } else {
            self::libAutoload();
        }
    }

    private static function setRoot(): void
    {
        $root = \dirname(__DIR__, self::$level);
        define('ROOT', $root);
    }

    private static function setAppBase(): void
    {
        self::require(ROOT . '/config.php');
        $appBase = preg_replace('/\/$/', '', APP_BASE);
        if (\preg_match('/www\.?/', APP_BASE)) {
            $appBase = preg_split('/www\.?/', APP_BASE);
            $appBase = preg_replace('/\/$/', '', array_pop($appBase));
        } else {
            $appBase = preg_replace('/\/$/', '', APP_BASE);
        }
        define('APPBASE', $appBase);
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