<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib/Support/constants.php';

define('ROOT', __DIR__);

require_once __DIR__ . '/lib/autoload.php';

@set_exception_handler([\Lib\Exceptions\CallException::class, 'renderView']);

require_once __DIR__ . '/route.php';

Lib\Http\CreateRoute::run();
