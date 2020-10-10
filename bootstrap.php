<?php

require_once 'config.php';
require_once 'lib/Support/constants.php';
require_once 'lib/autoload.php';

@set_exception_handler([\Lib\Exceptions\CallException::class, 'renderView']);

require_once 'route.php';

Lib\Http\CreateRoute::run();
