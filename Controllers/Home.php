<?php

namespace Controllers;

use Lib\Controllers\Controller;

class Home extends Controller
{
    public function __construct()
    {
        
    }
    public function index()
    {
        $this
        ->render("index", null)
        ->templete('templete', 'templete')
        ->components(['components' => 'component'])
        ->assets(['css' => 'style'], ['js' => 'script', 'js2' => 'script']);
    }
}
