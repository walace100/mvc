<?php

/**
 * Define o diretório que será ignorado pelo sistema na criação das rotas.
 * Exemplo: localhost/mvc, o mvc será ignorado pelo sistema, e será dado como a rota /.
 */

define('APP_BASE', 'mvc');

/**
 * Aqui será definido as constantes para iniciar o banco de dados.
 */

define('DBHOST', 'localhost');
define('DBNAME', 'teste');
define('DBUSER', 'root');
define('DBPASSWORD', '');

/**
 * Define o charset do banco de dados, não mexa se não souber.
 */

define('DBCHARSET', 'UTF8');
