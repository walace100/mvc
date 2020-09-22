<?php

require_once 'config.php';
require_once 'lib/Support/constants.php';
require_once 'lib/autoload.php';

use Lib\Http\CreateRoute;
use Lib\Http\Route;
use Lib\Http\Request;

CreateRoute::get('/{a}', 'a');
CreateRoute::run();

Route::get("/", function(){
    echo "fodase caralho";
});

Route::any("/branco", function(Request $req){
    echo Route::string('/');
});

Route::any("/eae1", function(){
    echo "eae";
});

Route::get("/eaemano", function(){
    echo "<form action='eaemano1/1'><input type='text' name='ae'><br><button>enviar</button></form>";
});

Route::get("get", function(){
    echo "<form method='post' action='get/aff'><input type='text' name='ae'><br><button>enviar</button></form>";
});

Route::any("/oi/aff", "Home", "index");

Route::get("get1", function(){
    echo "<form action='get2'><input type='text' name='ae'><br><button>enviar</button></form>";
});

Route::any("get2", function(Request $re){
    var_dump($re->has('w')->post()->w);
});

Route::get("/{afw}", function(){
    echo "fodase caralho2";
});

Route::post("get/{af}", function($af){
    echo $af;
});

Route::any("/eaemano1/{1}/", function($val2, Request $val){
    // echo $val2 . PHP_EOL;
    // var_dump($val->get());
    echo $val->get()->ae;
});

Route::any("/aff/{num}/{num2}", "Index", "index");

Route::any("/{oi}/video/{aff}", function ($a, $b){
    echo "$a $b";
});

Route::get("/eae/{oi}/{eae}/fawf/{eawe}/", function($a, $b, $c){
    var_dump('<pre>' ,$a, $b, $c);
});

Route::run();
