<?php

namespace Lib\Models;

use Lib\Models\DBConnection;

abstract class Model extends DBConnection
{
    private $table;

    private $connection;

    public function __construct()
    {
        $this->connection = new DBConnection(); 
    }

    public function query(string $query): ?array
    {

    }

    public function insert(string $query): void
    {

    }

    public function find(string $attributes, string $value, string $table = ''): array
    {

    }

    public function all(string $table = ''): array
    {

    }

    public function setTable(string $table): void
    {
        $this->table = $table;
    }
}
