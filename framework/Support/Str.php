<?php

namespace Framework\Support;

class Str
{
    public static function snake($value, $delimiter = '_')
    {
        $value = preg_replace('/\s+/u', '', ucwords($value));

        $value = strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $value));

        return $value;
    }

    public static function plural($value)
    {
        if (substr($value, -1) !== 's') {
            $value .= 's';
        }

        return $value;
    }

    public static function after($subject, $search)
    {
        return $search === '' ? $subject : array_reverse(explode($search, $subject, 2))[0];
    }
}