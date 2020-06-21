<?php


namespace Core\Essetials;


class Repository
{

    private static $repositories;

    public static function start() {
        static::$repositories = [];
    }

    public static function get($name) {
        return self::$repositories[$name];
    }

    public static function set($name, $repository) {
        self::$repositories[$name] = $repository;
    }

}