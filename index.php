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

Route::post("/oi/aff", "Home", "index");

Route::get("/eaemano", function(){
    echo "<form><input type='text' name='ae'><br><button>enviar</enviar></form>";
});

Route::get("/eae/{oi}/{eae}/{eae1}/eawe/", function(){
});

Route::run();
