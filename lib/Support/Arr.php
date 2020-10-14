<?php

namespace Lib\Support;

use Lib\Exceptions\GeralException;

class Arr
{
    public static function isAssoc(array $array): bool
    {
        try {
            return count(array_filter(array_keys($array), 'is_string')) > 0;
        }catch (\Exception $e) {
            throw new GeralException('ocorreu um erro: ' . $e->getMessage());
        }
    }

    public static function every(array $array, $callback): bool
    {
        try {
            return !in_array(false, array_map($callback, $array));
        } catch (\Exception $e) {
            throw new GeralException('ocorreu um erro: ' . $e->getMessage());
        }
    }
}
