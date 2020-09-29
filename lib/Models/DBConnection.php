<?php

namespace Lib\Models;

use PDO;
use PDOException;

abstract class DBConnection extends PDO
{
    protected $connection;

    public function __construct()
    {
        $this->connection = $this->connection();
    }

    private function connection()
    {
        $dsn = 'mysql:host=' . DBHOST . ';dbname=' . DBNAME . ';charset=' . DBCHARSET;
        $user = DBUSER;
        $password = DBPASSWORD;

        try {
            $connection = new PDO($dsn, $user, $password);
            return $connection;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
