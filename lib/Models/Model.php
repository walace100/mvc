<?php

namespace Lib\Models;

use Lib\Models\DBConnection;
use PDO;
use PDOStatement;

abstract class Model extends DBConnection
{
    protected $table = null;
    
    public function query(string $query, array $arguments = []): PDOStatement
    {
        parent::__construct();
        $statement = $this->connection->prepare($query);
        $this->setParam($statement, $arguments);
        $statement->execute();
        return $statement;
    }

    public function insert(array $attributes, array $values, string $table = null): void
    {

    }

    public function find(string $attribute, string $value, array $attributes, string $table = null)
    {

    }

    public function all(array $attributes = null, string $table = null): array
    {
        $attribute = $attributes ? implode(',', $attributes): '*';
        $table = $table ?? $this->table;

        $sql = 'SELECT ' . $attribute . ' FROM ' . $table . ' LIMIT 1000000';
        $statement = $this->query($sql);
        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    private function setParam($statement, array $arguments): void
    {
        foreach ($arguments as $key => $arg) {

            if (\is_string($key)) {
                $statement->bindParam($key, $arg);
            } else {
                $statement->bindParam($key + 1, $arg);
            }

        }
    }
}
