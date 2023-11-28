<?php

namespace Framework\Support;

/**
 * Class Str
 *
 * The Str class provides string manipulation methods.
 *
 * @package Framework\Support
 */
class Str
{
    /**
     * Convert a string to snake case.
     *
     * @param  string  $value  The input string.
     * @param  string  $delimiter  The delimiter used between words (optional, default is '_').
     * @return string  The snake case formatted string.
     */
    public static function snake($value, $delimiter = '_')
    {
        $value = preg_replace('/\s+/u', '', ucwords($value));

        $value = strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $value));

        return $value;
    }

    /**
     * Pluralize a string.
     *
     * @param  string  $value  The input string.
     * @return string  The pluralized string.
     */
    public static function plural($value)
    {
        if (substr($value, -1) !== 's') {
            $value .= 's';
        }

        return $value;
    }

    /**
     * Get the portion of a string after a given value.
     *
     * @param  string  $subject  The input string.
     * @param  string  $search  The value to search for.
     * @return string  The portion of the string after the given value.
     */
    public static function after($subject, $search)
    {
        return $search === '' ? $subject : array_reverse(explode($search, $subject, 2))[0];
    }

    /**
     * Cap a string with a single instance of a given value.
     *
     * @param  string  $value  The input string.
     * @param  string  $cap  The value to cap the string with.
     * @return string  The capped string.
     */
    public static function finish($value, $cap)
    {
        $quoted = preg_quote($cap, '/');

        return preg_replace('/(?:'.$quoted.')+$/u', '', $value).$cap;
    }
}