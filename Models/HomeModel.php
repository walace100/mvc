<?php

namespace Models;

use Lib\Models\Model;

class HomeModel extends Model
{
    public function index()
    {
        // define a tabela que será usada.
        #$this->table = 'table';

        // insere novos valores no banco.
        #$query = $this->insert(['campo1', 'campo1', 'campo3'], [valor1, valor2, valor3]);

        // executa uma query específica.
        // Primeiro parâmetro: a query,
        // segundo parâmetro: um array associativo com 'campo' => 'valor' que será blindado.
        #$query = $this->query(query, ['?' => 'valor']);

        // Atualiza valores no banco de dados.
        // Primeiro parâmetro: array associativo com: 'campo' => 'valor' que será definido,
        // Segundo parâmetro: array associativo com: 'campo' => 'valor' que será comparado no banco.
        #$this->update(['campoSet' => 'valorSet'], ['campoWhere1' => 'valorWhere1', 'campoWhere2' => 'valorWhere2']);

        // Retorna todos os registros do banco de dados.
        #$query = $this->all();
    }
}
