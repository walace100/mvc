<?php

namespace Controllers;

use Lib\Controllers\Controller;

class Home extends Controller
{
    public function __construct()
    {
        echo "<h1>fawfaw</h1>";
        
    }
    public function index()
    {
        $this->render("index")->templete('templete', 'templete')->components(['components' => 'component']);
        $this->assets(['css' => 'style'], ['js' => 'script']);
    }
}
