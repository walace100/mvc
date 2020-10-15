<?php

/**
 * Inclui as configs para a iniciação do sistema.
 */

require_once __DIR__ . '/config.php';

/**
 * Inclui as constantes que serão usadas pelo sistema.
 */

require_once __DIR__ . '/lib/Support/constants.php';

/**
 * Define a constante ROOT com valor da raiz do projeto.
 */

define('ROOT', __DIR__);

/**
 * Inclui o autoload do sistema onde verifica se a pasta 'vendor' existe,
 * Se existir incluirá o autoload do composer, senão usará o autoload do sistema.
 */

require_once __DIR__ . '/lib/autoload.php';

/**
 * Define a classe padrão para as exceptions.
 */

@set_exception_handler([\Lib\Exceptions\CallException::class, 'renderView']);

/**
 * Inclui as rotas e as inicia.
 */

require_once __DIR__ . '/route.php';

Lib\Http\CreateRoute::run();
