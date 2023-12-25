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

    /**
     * Set a flash message.
     *
     * @param  string  $key
     * @param  string  $message
     * @return void
     */
    public static function setFlashMessage($key, $message)
    {
        static::set('flash_'.$key, $message);
    }

    /**
     * Get a flash message.
     *
     * @param  string  $key
     * @return mixed
     */
    public static function getFlashMessage($key)
    {
        $message = static::get('flash_'.$key) ?? null;
        static::clear('flash_'.$key);

        return $message;
    }

    /**
     * Check if a flash message exists.
     *
     * @param  string  $key
     * @return bool
     */
    public static function hasFlashMessage($key)
    {
        return static::has('flash_'.$key);
    }
}
