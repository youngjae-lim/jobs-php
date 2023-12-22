<?php

namespace Framework;

class Validation
{
    /**
     * Validate a string.
     *
     * @param  string  $value
     * @param  int  $min
     * @param  int  $max
     * @return bool
     */
    public static function string($value, $min = 1, $max = INF)
    {
        if (!is_string(trim($value))) {
            return false;
        }
        if (strlen($value) < $min || strlen($value) > $max) {
            return false;
        }
        return true;
    }

    /**
     * Validate an email.
     *
     * @param  string  $value
     * @return mixed
     */
    public static function email($value)
    {
        return filter_var(trim($value), FILTER_VALIDATE_EMAIL);
    }

    /**
     * Match two string values.
     *
     * @param  string  $value
     * @param  string  $compare
     * @return bool
     */
    public static function match($value, $compare)
    {
        return trim($value) === trim($compare);
    }
}
