<?php

namespace Controllers;

use Lib\Controllers\Controller;
use Models\Index as ModelsIndex;

class Index extends Controller
{
    public function index($eae, $af)
    {
        // echo "$eae $af";
        new ModelsIndex();
    }
}
