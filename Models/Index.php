<?php

namespace Models;

use Lib\Models\Model;
use PDO;

class Index extends Model
{
    public function index()
    {
        $this->table = 'teste';
        // $query = $this->insert(['id', 'nome', 'idade'], [$id, $nome, $idade]);
        // $query = $this->query("SELECT * FROM teste WHERE id = :id", [':id' => '1']);
        // $query = $this->update(['nome' => 'josué'], ['id' => '6']);
        $query = $this->all();
        // var_dump('<pre>',$query->fetchAll(PDO::FETCH_CLASS));
        var_dump('<pre>',$query);
    }
}
