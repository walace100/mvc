<?php

namespace Lib\Models;

use PDO;
use PDOException;

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
            var_dump($connection);
            return $connection;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    private function charset(): void
    {
        $sql = "
            SET NAMES '" . DBCHARSET ."';
            SET CHARACTER_SET_CONNECTION = '" . DBCHARSET ."';
            SET CHARACTER_SET_RESULTS = '" . DBCHARSET ."'
        ";
        $this->connection->query($sql);
    }
}
