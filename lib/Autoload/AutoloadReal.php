<?php

namespace Lib\Autoload;

final class AutoloadReal
{
    /**
     * Verifica se o autoload do composer existe, e o chama,
     * Senão chamará o autoload do próprio sistema.
     * 
     * @return void
     */
    public static function bootstrap(): void
    {
        if (file_exists(ROOT . '/vendor/autoload.php')) {
            self::composerAutoload();
        } else {
            self::libAutoload();
        }
    }

    /**
     * Inclui o autoload do composer.
     * 
     * @return void
     */
    private static function composerAutoload(): void
    {
        self::require(ROOT . '/vendor/autoload.php');
    }

    /**
     * Registra o autoload do sistema.
     * 
     * @return void
     */
    private static function libAutoload(): void
    {
        \spl_autoload_register('self::autoload');
    }

    /**
     * Inclui as classes automaticamente.
     * 
     * @return void
     */
    private static function autoload($class): void
    {
        $namespace = \str_replace('\\', '/', $class);
        $finalClass = \str_replace('Lib', 'lib', $class);
        self::require(ROOT . '/' . $finalClass . '.php');
    }

    /**
     * Inclui arquivos.
     * 
     * @param  string  $path
     * @return void
     */
    private static function require(string $path): void
    {
        require_once $path;
    }
}