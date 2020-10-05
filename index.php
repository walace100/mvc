<?php

require_once 'config.php';
require_once 'lib/Support/constants.php';
require_once 'lib/autoload.php';
require_once 'route.php';

use Lib\Http\CreateRoute as Route;

Route::run();
