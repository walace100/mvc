<?php

namespace Controllers;

use Lib\Controllers\Controller;
use Lib\Exceptions\GeralException;
use Models\Index as ModelsIndex;

class Index extends Controller
{
    public function __construct()
    {
    }
    public function index($eae, $af)
    {
        try {
            throw new GeralException('aa');
        } catch(GeralException $e) {
            // var_dump($e);
        }
        new ModelsIndex();
    }
}
