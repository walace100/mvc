<?php

namespace Controllers;

use Lib\Controllers\Controller;
use Lib\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $this
        ->render("index", null)
        ->templete('templete', 'templete')
        ->components(['components' => 'component'])
        ->assets(['css' => 'style'], ['js' => 'script', 'js2' => 'script']);
    }
}
