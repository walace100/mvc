<?php

namespace Controllers;

use Lib\Controllers\Controller;
use Lib\Http\Request;

class HomeController extends Controller
{
    /**
     * Método inicial.
     */
    public function index(Request $request)
    {
        // primeiro parâmetro é o nome da view e o segundo é os parâmetros para a view.
        $this->render('index', null)
        
        // primeiro parâmetro é o apelido do templete e o segundo é o nome do templete.
        ->templete('templeteApelido', 'templete')

        // O parâmetro é um array associativo com apelido => nome do arquivo.
        ->components(['componenteApelido' => 'component'])

        // O parâmetro é um array associativo com apelido => nome do arquivo do CSS,
        // e o segundo também, mas do javascript
        ->assets(['cssApelido' => 'style'], ['jsApelido' => 'script']);

        // retorna um object com $_GET.
        #$request->get();

         // retorna um object com $POST.
        #$request->post();

        // retorna o valor se existir, senão retorna NULL.
        #$request->has('val1')->get()->val1;
    }

    /**
     * Cria um novo item.
     */
    public function create()
    {

    }

    /**
     * Mostra itens.
     */
    public function show()
    {

    }

    /**
     * Atualiza um item.
     */
    public function update()
    {

    }

    /**
     * Apaga um item.
     */
    public function delete()
    {

    }
}
