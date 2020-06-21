<?php
    define("DEFAULT_TITLE", "Framework");
    define("GENERAL_EXTENSION", "php");
    define("_D_DIRECTORY", __DIR__);
    define("ROOT", __DIR__);
    define("DS", DIRECTORY_SEPARATOR);

    define("CONFIG_PATH", "app/Config/");

    define('DEBUG', true);

    defined("CACHE_PATH") or define("CACHE_PATH", dirname(dirname(__DIR__)) . DS . "app" . DS . "Cache");