<?php

namespace Models;

use Lib\Models\Model;
use PDO;

class Index extends Model
{
    public function __construct()
    {
        $this->index();
    }
    public function index()
    {
        $this->table = 'teste';
        // $query = $this->insert(['id', 'nome', 'idade'], [$id, $nome, $idade]);
        // $query = $this->query("SELECT * FROM teste WHERE id = :id", [':id' => '1']);
        // $this->update(['nome' => 'teste'], ['id' => '4', 'nome' => 'Douglas']);
        $query = $this->all();
        // var_dump('<pre>',$query);
    }
}
