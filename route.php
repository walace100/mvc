<?php

use Lib\Http\CreateRoute as Route;
use Lib\Http\Request;

/**
 * Aqui você pode registrar as suas rotas para seus Controllers ou Callbacks.
 */

Route::get("/", "HomeController", "index");
