<?php

// Se o APP_BASE for maior que 1 ele retirará a / no final,
// senão não fará nada.
if (strlen(APP_BASE) > 1) {
    $appBase = preg_replace('/\/$/', '', APP_BASE);
} elseif (strlen(APP_BASE) === 0) {
    $appBase = '/';
} else {
    $appBase = APP_BASE;
}


// Junta o domínio com o diretório
if (!\preg_match('/^(www\.)?' . $_SERVER['HTTP_HOST'] . '/', $appBase)) {
    if (!preg_match('/^\//', $appBase)) {
        $appBase = $_SERVER['HTTP_HOST'] . '/' . $appBase;
    } else {
        $appBase = $_SERVER['HTTP_HOST'] . $appBase;
    }
}

// Retira o www. do começo do APP_BASE
if (\preg_match('/^www\.?/', APP_BASE)) {
    $appBase = preg_split('/www\.?/', APP_BASE);
}

// Define o APPBASE
define('APPBASE', $appBase);
