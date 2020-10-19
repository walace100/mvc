<?php

namespace Lib\Models;

use Lib\Exceptions\modelException;
use Lib\Models\DBConnection;
use PDO;
use PDOStatement;
use PDOException;
use Lib\Support\Arr;

abstract class Model extends DBConnection
{
    /**
     * Armazena o valor da tabela.
     * 
     * @var string|null
     */
    public $table = null;

    /**
     * Controla como a próxima linha será retornada ao chamador.
     * 
     * @var int
     */
    protected $fetch_style = PDO::FETCH_CLASS;

    /**
     * Executa uma query e retorna um statement.
     * 
     * @param  string  $query
     * @param  mixed  $arguments
     * @return \PDOStatement
     * 
     * @throws \Lib\Models\ModelException
     */
    public function querySt(string $query, $arguments = []): PDOStatement
    {
        parent::__construct();
        try {
            $statement = $this->connection->prepare($query);
            $this->setValue($statement, $arguments);
            $statement->execute();
            return $statement;
        } catch (PDOException $e) {
            throw new ModelException('ocorreu um erro: ' . $e->getMessage() . '<br> SQL: ' . $query);
        }
    }

    /**
     * Insere valores no banco de dados.
     * 
     * @param  array  $attributes
     * @param  array  $values
     * @param  string|null  $table
     * @return void
     */
    public function insert(array $attributes, array $values, ?string $table = null): void
    {
        [$fields, $table] = $this->setFields($attributes, $table);
        $protectedValues = implode(',', array_fill(0, count($values), '?'));

        $sql = "INSERT INTO $table ($fields) VALUES ($protectedValues)";
        $this->querySt($sql, $values);
    }

    /**
     * Encontra um registro no banco de dados.
     * 
     * @param  string  $attribute
     * @param  string  $value
     * @param  array|null  $fields
     * @param  string|null  $table
     * @return array
     */
    public function find(string $attribute, string $value, ?array $fields = null, ?string $table = null): array
    {
        [$newfields, $table] = $this->setFields($fields, $table);

        $sql = "SELECT $newfields FROM $table WHERE $attribute = ?";
        $statement = $this->querySt($sql, $value);
        return $statement->fetchAll($this->fetch_style);
    }

    /**
     * Encontra todos os registros no banco de dados.
     * 
     * @param  array|null  $attributes
     * @param  string|null  $table
     * @param  int|null  $limit
     * @return array
     */
    public function all(?array $attributes = null, ?string $table = null, int $limit = 1000000): array
    {
        [$attribute, $table] = $this->setFields($attributes, $table);

        $sql = "SELECT $attribute FROM $table LIMIT $limit";
        $statement = $this->querySt($sql);
        return $statement->fetchAll($this->fetch_style);
    }

    /**
     * Atualiza um registro do banco de dados.
     * 
     * @param  array  $setValueAssoc
     * @param  array  $compareValueAssoc
     * @param  string|null  $table
     * @param  int|null  $limit
     * @return void
     * 
     * @throws \Lib\Exceptions\ModelException
     */
    public function update(array $setValueAssoc, array $compareValueAssoc, ?string $table = null, int $limit = 1): void
    {
        if (!Arr::isAssoc($setValueAssoc) || !Arr::isAssoc($compareValueAssoc)) {
            throw new ModelException('parâmetros passados não são array associativo');
        }

        $logicAND = count($compareValueAssoc) > 1 ? true: false;
        $logicPos = $logicAND ? 1: null;

        [, $table] = $this->setFields(null, $table);
        [$set, $where] = $this->setCompareFields([$setValueAssoc, $compareValueAssoc], $logicAND, $logicPos);
        $values = [...array_values($setValueAssoc), ...array_values($compareValueAssoc)];

        $sql = "UPDATE $table SET $set WHERE $where";
        $this->querySt($sql, $values);
    }

    /**
     * Atualiza um registro do banco de dados.
     * 
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  string|null  $table
     * @param  int|null  $limit
     * @return void
     */
    public function delete(string $attribute, $value, ?string $table = null, int $limit = 1): void
    {
        $table = $table ?? $this->table;
        $sql = "DELETE FROM $table WHERE $attribute = ? LIMIT $limit";
        $this->querySt($sql, $value);
    }

    /**
     * Blinda os parâmetros antes de entrar no banco de dados.
     * 
     * @param  \PDOStatement  $statement
     * @param  mixed  $arguments
     * @return void
     * 
     * @throws \Lib\Exceptions\ModelException
     */
    private function setValue(PDOStatement $statement, $arguments): void
    {
        try {
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
        } catch (PDOException $e) {
            throw new ModelException('ocorreu um erro: ' . $e->getMessage());
        }
    }

    /**
     * Se existir, junta os valores por vírgula, senão retorna *.
     * Retorna a tabela da classe se $table não for definido.
     * 
     * @param  mixed  $attributes
     * @param  string|null  $table
     * 
     * @return array
     */
    private function setFields($attributes, ?string $table = null): array
    {
        $fields = $attributes ? implode(',', $attributes): '*';
        $table = $table ?? $this->table;
        return [$fields, $table];
    }

    /**
     * Compara os valores e junta por vírgula, se tiver o $logicAND juntará por AND
     * @param  array  $fields
     * @param  bool  $logicAND
     * @param  int|null  $logicPos
     * 
     * @return array
     */
    private function setCompareFields(array $fields, bool $logicAND = false, ?int $logicPos): array
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
