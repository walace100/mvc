<?php

namespace Lib\Models;

use PDO;
use PDOException;
use Lib\Exceptions\ModelException;

abstract class DBConnection extends PDO
{
    protected $connection = false;

    public function __construct()
    {
        $this->connection = $this->connection();
        $this->charset();
    }

    public function connection(): PDO
    {
        $dsn = 'mysql:host=' . DBHOST . ';dbname=' . DBNAME . ';charset=' . DBCHARSET;
        $user = DBUSER;
        $password = DBPASSWORD;

        try {
            $connection = new PDO($dsn, $user, $password);
            return $connection;
        } catch (PDOException $e) {
            throw new ModelException('ocorreu um erro: ' . $e->getMessage());
        }
    }

    private function charset(): void
    {
        $sql = "
            SET NAMES '" . DBCHARSET ."';
            SET CHARACTER_SET_CONNECTION = '" . DBCHARSET ."';
            SET CHARACTER_SET_RESULTS = '" . DBCHARSET ."'
        ";

        try {
            $this->connection->query($sql);
        } catch (PDOException $e) {
            throw new ModelException('ocorreu um erro: ' . $e->getMessage());
        }
    }
}
