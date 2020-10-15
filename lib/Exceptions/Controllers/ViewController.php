<?php

namespace Lib\Exceptions\Controllers;

use Lib\Controllers\Controller;

class ViewController extends Controller
{
     /**
     * Armazena a view.
     * 
     * @var string
     */
    private $view;

    /**
     * Armazena os parâmetros da Exception
     * 
     * @var array
     */
    private $param;

    /**
     * Define o caminho da view.
     * 
     * Define o caminho do CSS.
     * 
     * Define a view.
     * 
     * Define os parâmetros da Exception.
     * 
     * Chama o método init.
     * 
     * @param  string  $view
     * @param  array  $param
     * @return void
     */
    public function __construct(string $view, array $param)
    {
        $this->viewPath = '/lib/Exceptions/views/';
        $this->cssPath = '';
        $this->view = $view;
        $this->param = $param;
        $this->init();
    }

    /**
     * Define a view, assets, templete e componentes e o inicia.
     * 
     * @return void
     */
    private function init()
    {
        $this->render($this->view, $this->param)
        ->assets(['css' => 'style'])
        ->templete('view', 'templete')
        ->components(['title' => 'viewExceptionController']);
    }
}
