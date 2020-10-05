<?php

namespace Controllers;

use Lib\Controllers\Controller;
use Models\Index as ModelsIndex;

class Index extends Controller
{
    public function __construct()
    {
        // echo "<h1>teste2</h1>";
    }
    public function index($eae, $af)
    {
        // echo "$eae $af";
        new ModelsIndex();
    }
}
