<?php

if (strlen(APP_BASE) > 1) {
    $appBase = preg_replace('/\/$/', '', APP_BASE);
} elseif (strlen(APP_BASE) === 0) {
    $appBase = '/';
} else {
    $appBase = APP_BASE;
}

if (!\preg_match('/^(www\.)?' . $_SERVER['HTTP_HOST'] . '/', $appBase)) {
    if (!preg_match('/^\//', $appBase)) {
        $appBase = $_SERVER['HTTP_HOST'] . '/' . $appBase;
    } else {
        $appBase = $_SERVER['HTTP_HOST'] . $appBase;
    }
}

if (\preg_match('/^www\.?/', APP_BASE)) {
    $appBase = preg_split('/www\.?/', APP_BASE);
}

define('APPBASE', $appBase);
