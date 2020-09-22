<?php

$levels = 2;
$root = \dirname(__DIR__, $levels);
$appBase = preg_replace('/\/$/', '', APP_BASE);

if (\preg_match('/www\.?/', APP_BASE)) {
    $appBase = preg_split('/www\.?/', APP_BASE);
    $appBase = preg_replace('/\/$/', '', array_pop($appBase));
} else {
    $appBase = preg_replace('/\/$/', '', APP_BASE);
}

define('ROOT', $root);
define('APPBASE', $appBase);
