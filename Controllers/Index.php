<?php

namespace Controllers;

use Lib\Controllers\Controller;

class Index extends Controller
{
    public function index($eae, $af)
    {
        echo "$eae $af";
    }
}
