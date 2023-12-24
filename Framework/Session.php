<?php

namespace Framework;

class Session
{
    /**
     * Start the session.
     *
     * @return void
     */
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a session variable.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session variable.
     *
     * @param  string  $key
     * @return mixed
     */
    public static function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Check if a session variable exists.
     *
     * @param  string  $key
     * @return bool
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Clear a session variable.
     *
     * @param  string  $key
     * @return void
     */
    public static function clear($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Clear all session variables.
     *
     * @return void
     */
    public static function clearAll()
    {
        session_unset();
        session_destroy();
    }
}
