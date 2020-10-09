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
        $oi = 'wadawd';
        $this->render("index", null)->templete('templete', 'templete')->components(['components' => 'component']);
        $this->assets(['css' => 'style'], ['js' => 'script']);
    }
}
