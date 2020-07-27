<?php

namespace Controllers;
use Lib\Controllers\Controller;

class Home extends Controller
{
    public function index()
    {
        $this->render("index");
        // echo "oi";
    }
}
