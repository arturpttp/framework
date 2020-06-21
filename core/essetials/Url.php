<?php


namespace Core\Essetials;


class Url {
    /**
     * Url constructor
     */
    private function __construct() {}

    /**
     * Get path
     *
     * @param string $path
     * @return string $path
     */
    public static function path($path) {
        return Request::baseUrl() . '/' . trim($path, '/');
    }

    /**
     * Previous url
     *
     * @return string
     */
    public static function previous() {
        return Request::server('HTTP_REFERER');
    }

    /**
     * Redirect to page
     *
     * @return void
     */
    public static function redirect($path) {
        header('location: ' . $path);
        exit();
    }
}