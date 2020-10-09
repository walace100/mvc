<?php

namespace Controllers;

use Lib\Controllers\Controller;
use Lib\Exceptions\GeralException;
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
        try {
            throw new GeralException('a');
        } catch(GeralException $e) {
        }
        new ModelsIndex();
    }
}
