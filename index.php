<?php

require_once 'lib/autoload.php';

use Lib\Routes\Route;

Route::get("/", function(){
    echo "fodase caralho";
});

Route::get("/eae1", function(){
    echo "eae";
});

Route::post("/aff/{num}/{num2}", "Index", "index");

Route::post("/oi/aff", "Home", "index");

Route::get("/eaemano", function(){
    echo "<form><input type='text' name='ae'><br><button>enviar</enviar></form>";
});

Route::get("/eae/{oi}/{eae}/fawf/{eawe}/", function($a, $b, $c){
    var_dump('<pre>' ,$a, $b, $c);
});

Route::get("/{oi}/video/{aff}", function ($a, $b){
    echo "video";
});

Route::run();
