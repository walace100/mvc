<?php

namespace Lib\Models;

use Exception;
use Lib\Models\DBConnection;
use PDO;
use PDOStatement;
use PDOException;

abstract class Model extends DBConnection
{
    public $table = null;

    public function querySt(string $query, $arguments = []): PDOStatement
    {
        parent::__construct();
        try {
            $statement = $this->connection->prepare($query);
            $this->setValue($statement, $arguments);
            $statement->execute();
            return $statement;
        } catch (PDOException | Exception $e) {
            echo $e;
        }
    }

    public function insert(array $attributes, array $values, ?string $table = null): void
    {
        [$fields, $table] = $this->setFields($attributes, $table);
        $protectedValues = implode(',', array_fill(0, count($values), '?'));

        $sql = "INSERT INTO $table ($fields) VALUES ($protectedValues)";
        $this->querySt($sql, $values);
    }

    public function find(string $attribute, string $value, ?array $fields = null, ?string $table = null): array
    {
        [$newfields, $table] = $this->setFields($fields, $table);

        $sql = "SELECT $newfields FROM $table WHERE $attribute = ?";
        $statement = $this->querySt($sql, $value);
        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    public function all(?array $attributes = null, ?string $table = null, int $limit = 1000000): array
    {
        [$attribute, $table] = $this->setFields($attributes, $table);

        $sql = "SELECT $attribute FROM $table LIMIT $limit";
        $statement = $this->querySt($sql);
        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    public function update(array $setValueAssoc, array $compareValueAssoc, ?string $table = null, int $limit = 1): void
    {
        $logicAND = count($compareValueAssoc) > 1 ? true: false;
        $logicPos = $logicAND ? 1: null;

        [, $table] = $this->setFields(null, $table);
        [$set, $where] = $this->setCompareFields([$setValueAssoc, $compareValueAssoc], $logicAND, $logicPos);
        $values = [...array_values($setValueAssoc), ...array_values($compareValueAssoc)];

        $sql = "UPDATE $table SET $set WHERE $where";
        $this->querySt($sql, $values);
    }

    public function delete(string $attribute, $value, ?string $table = null, int $limit = 1): void
    {
        $table = $table ?? $this->table;
        $sql = "DELETE FROM $table WHERE $attribute = ? LIMIT $limit";
        $this->querySt($sql, $value);
    }

    private function setValue(PDOStatement $statement, $arguments): void
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

    private function setFields($attributes, ?string $table = null): array
    {
        $fields = $attributes ? implode(',', $attributes): '*';
        $table = $table ?? $this->table;
        return [$fields, $table];
    }

    private function setCompareFields(array $fields, bool $logicAND, ?int $logicPos): array
    {
        foreach ($fields as $key => $field) {
            foreach (array_keys($field) as $value) {
                $setfields[$key][] = $value . ' = ?';

                if (!is_null($logicPos) && $logicAND) {
                    $finalFields[$key] = implode(' AND ', $setfields[$key]);
                } else {
                    $finalFields[$key] = implode(', ', $setfields[$key]);
                }
            }
        }
        return $finalFields;
    }
}
