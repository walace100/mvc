<?php

namespace Lib\Support;

class Arr
{
    public static function isAssoc(array $array): bool
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }
}
