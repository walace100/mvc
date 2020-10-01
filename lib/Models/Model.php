<?php

namespace Lib\Models;

use Lib\Models\DBConnection;
use LimitIterator;
use PDO;
use PDOStatement;

abstract class Model extends DBConnection
{
    protected $table = null;
    
    public function query(string $query, $arguments = []): PDOStatement #
    {
        parent::__construct();
        $statement = $this->connection->prepare($query);
        $this->setParam($statement, $arguments);
        $statement->execute();
        return $statement;
    }

    public function insert(array $attributes, array $values, string $table = null): void
    {
        [$fields, $table] = $this->setFields($attributes, $table);
        $protectedValues = implode(',', array_fill(0, count($values), '?'));

        $sql = "INSERT INTO $table ($fields) VALUES ($protectedValues)";
        $this->query($sql, $values);
    }

    public function find(string $attribute, string $value, array $fields = null, string $table = null): array
    {
        [$newfields, $table] = $this->setFields($fields, $table);

        $sql = "SELECT $newfields FROM $table WHERE $attribute = ?";
        $statement = $this->query($sql, $value);
        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    public function all(array $attributes = null, string $table = null, int $limit = 1000000): array
    {
        [$attribute, $table] = $this->setFields($attributes, $table);

        $sql = "SELECT $attribute FROM $table LIMIT $limit";
        $statement = $this->query($sql);
        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    // public function update(array $newValueAssoc, array $compareValueAssoc, int $limit = 1): void
    // {
        
    // }

    public function delete(string $attribute, $value, string $table = null, int $limit = 1): void
    {
        $table = $table ?? $this->table;
        $sql = "DELETE FROM $table WHERE $attribute = ? LIMIT $limit";
        $this->query($sql, $value);
    }

    private function setParam(PDOStatement $statement, $arguments): void
    {
        if (!is_array($arguments)) {
            $statement->bindValue(1, $arguments);
            return;
        }

        foreach ($arguments as $key => $arg) {

            if (\is_string($key)) {
                $statement->bindValue($key, $arg);
            } else {
                $statement->bindValue($key + 1, $arg);
            }

        }
    }

    private function setFields($attributes, $table = null): array
    {
        $fields = $attributes ? implode(',', $attributes): '*';
        $table = $table ?? $this->table;
        return [$fields, $table];
    }
}
