<?php


namespace Core\System;


trait Session
{

    public static $session;

    public static function start()
    {
        if (!session_id()) session_start();

        self::$session = new \stdClass();

        foreach ($_SESSION as $key => $value) {
            self::$session->$key = $value;
        }
    }

    public static function get($key, $defaults = null)
    {
        return self::has($key) ? $_SESSION[$key] : $defaults;
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
        self::$session->$key = $value;
    }

    public static function remove($key) {
        if (is_array($key))
            foreach ($key as $x)
                self::remove($x);
        else {
            unset($_SESSION[$key]);
            unset(self::$session->$key);
        }
    }

    public static function has($key) {
        $has = false;
        if (isset($_SESSION[$key]) && $_SESSION[$key] != null)
            $has = true;
        return $has;
    }

}