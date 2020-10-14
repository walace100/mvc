<?php

use Lib\Http\CreateRoute as Route;
use Lib\Http\Request;

Route::get("/", function(){
    echo "/";
});

Route::get("branco", function(Request $request){
    $request->session()->oi = 'daw';
    var_dump('<pre>', $request->session()->all());
});

Route::get("get", function(){
    echo "get";
});

Route::get("get/request", function(Request $request){
    echo "request";
});

Route::get("get/post/form", function(){
    echo "
        <form action='" . Route::string("post") . "' method='post'>
            <input name='teste'>
            <button type='submit'>Envar</button>
        </form>
    ";
});

Route::post("post", function(Request $request){
    echo $request->post()->teste;
    echo "<br><a href='". Route::string("get/post/form") . "'>voltar</a>";
});

Route::get("controller/{param1}/{param2}", "Index", "index");

Route::get("controller", "Home", "index");
