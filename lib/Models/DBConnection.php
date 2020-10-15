<?php

namespace Lib\Models;

use PDO;
use PDOException;
use Lib\Exceptions\ModelException;

abstract class DBConnection extends PDO
{
    /**
     * Armazena a conexão com o banco de dados, é recomendado iniciar o parent::connection()
     * antes de usar.
     * 
     * @var \PDO|bool
     */
    protected $connection = false;

    /**
     * Inicia a conexão e define o charset.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->connection = $this->connection();
        $this->charset();
    }

    /**
     * Inicia a conexão com o banco de dados.
     * 
     * @return \PDO
     * 
     * @throws \Lib\Exceptions\ModelException
     */
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

    /**
     * Define o charset de nome, conexão e resultado do banco de dados.
     * 
     * @return void
     * 
     * @throws \Lib\Exceptions\ModelException
     */
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
