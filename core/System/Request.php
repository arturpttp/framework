<?php


namespace Core\System;


trait Request
{
    private static $script_name;
    private static $base_url;
    private static $url;
    private static $full_url;
    private static $query_string;
    public static $post, $get;

    public static function start()
    {
        self::$post = new \stdClass();
        self::$get = new \stdClass();

        foreach ($_POST as $key => $value) {
            self::$post->$key = $value;
        }
        foreach ($_GET as $key => $value) {
            self::$get->$key = $value;
        }
    }

    public static function get($key) {
        return $_GET[$key];
    }

    public static function post($key) {
        return $_POST[$key];
    }

    public static function server($key) {
        return $_SERVER[$key];
    }

    public static function handle() {
        static::$script_name = str_replace('\\', '', dirname(Server::get('SCRIPT_NAME')));
        static::setBaseUrl();
        static::setUrl();
    }

    private static function setBaseUrl() {
        $protocol = self::server('REQUEST_SCHEME') . '://';
        $host = self::server('HTTP_HOST');
        $script_name = static::$script_name;

        static::$base_url = $protocol . $host . $script_name;
    }

    private static function setUrl() {
        $request_uri = urldecode(self::server('REQUEST_URI'));
        $request_uri = rtrim(preg_replace("#^" . static::$script_name. '#', '', $request_uri), '/');

        $query_string = '';

        static::$full_url = $request_uri;
        if (strpos($request_uri, '?') !== false) {
            list($request_uri, $query_string) = explode('?', $request_uri);
        }

        static::$url = $request_uri?:'/';
        static::$query_string = $query_string;
    }

    public static function baseUrl() {
        return static::$base_url;
    }

    public static function url() {
        return static::$url;
    }

    public static function query_string() {
        return static::$query_string;
    }

    public static function full_url() {
        return static::$full_url;
    }

    public static function method() {
        return self::server('REQUEST_METHOD');
    }

    public static function has($type, $key) {
        return array_key_exists($key, $type);
    }

    public static function set($key, $value) {
        $_REQUEST[$key] = $value;
        $_POST[$key] = $value;
        $_GET[$key] = $value;

        return $value;
    }

    public static function previous() {
        return self::server('HTTP_REFERER');
    }

    public static function all() {
        return $_REQUEST;
    }

}