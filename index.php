<?php

require_once 'lib/autoload.php';

use Lib\Http\Route;
use Lib\Http\Request;

Route::get("/", function(){
    echo "fodase caralho";
});

Route::get("/eae1", function(){
    echo "eae";
});

Route::get("/{a}", function(){
    echo "fodase";
});

Route::post("/aff/{num}/{num2}", "Index", "index");

Route::post("/oi/aff", "Home", "index");

Route::get("/eaemano", function(){
    echo "<form action='eaemano1/1'><input type='text' name='ae'><br><button>enviar</button></form>";
});

Route::get("/eaemano1/{1}/", function($val2, Request $val){
    echo $val2 . PHP_EOL;
    echo $val->a();
});

Route::get("/eae/{oi}/{eae}/fawf/{eawe}/", function($a, $b, $c){
    var_dump('<pre>' ,$a, $b, $c);
});

Route::get("/{oi}/video/{aff}", function ($a, $b){
    echo "$a $b";
});

Route::get("get", function(){
    echo "<form method='post' action='get/aff'><input type='text' name='ae'><br><button>enviar</button></form>";
});

Route::post("get/{af}", function($af){
    echo $af;
});

Route::run();
