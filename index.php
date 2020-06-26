<?php

require_once 'lib/autoload.php';

use Lib\Routes\Route;

Route::get("/", function(){
    echo "fodase caralho";
});

Route::get("/eae1", function(){
    echo "eae";
});

Route::post("/aff", "Index", "index");

Route::run();