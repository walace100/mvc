<?php

namespace Models;

use Lib\Models\Model;
use PDO;

class Index extends Model
{
    public function __construct()
    {
        $this->table = 'teste';
        // $query = $this->insert(['id', 'nome', 'idade'], [$id, $nome, $idade]);
        // $query = $this->query("SELECT * FROM teste WHERE id = :id", [':id' => '1']);
        $query = $this->update(['id' =>'5'], []);
        // var_dump('<pre>',$query->fetchAll(PDO::FETCH_CLASS));

    }
}
