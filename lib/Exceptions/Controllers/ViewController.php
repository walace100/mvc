<?php

namespace Lib\Exceptions\Controllers;

use Lib\Controllers\Controller;

class ViewController extends Controller
{
    private $view;

    private $param;

    public function __construct(string $view, array $param)
    {
        $this->viewPath = '/lib/Exceptions/views/';
        $this->cssPath = '';
        $this->view = $view;
        $this->param = $param;
        $this->init();
    }

    private function init()
    {
        $this->render($this->view, $this->param)
        ->assets(['css' => 'style'])
        ->templete('view', 'templete')
        ->components(['title' => 'viewExceptionController']);
    }
}
