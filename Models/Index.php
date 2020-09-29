<?php

namespace Models;

use Lib\Models\Model;
use PDO;

class Index extends Model
{
    public function __construct()
    {
        $this->table = 'teste';
        var_dump($this->all());
        $a = $this->query('SELECT * FROM teste;')->fetchAll(PDO::FETCH_CLASS);
        var_dump($a[0]->id);
    }
}
