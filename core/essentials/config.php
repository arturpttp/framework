<?php

date_default_timezone_set("America/Sao_Paulo");

define("PDO_DEFAULT_FETCH_MODE", \PDO::FETCH_ASSOC);
define("DEFAULT_TITLE", "Shadowz");
define("GENERAL_EXTENSION", "php");
define("ROOT", __DIR__);
define("DS", DIRECTORY_SEPARATOR);
define("CONFIG_PATH", "app/Config/");
define('DEBUG', true);
define("FRAMEWORK_INITIALIZE_TIME", time());
defined("CACHE_PATH") or define("CACHE_PATH", dirname(dirname(__DIR__)) . DS . "app" . DS . "Cache");
define("ROOT_PATH", dirname(dirname(__DIR__)));
define("BASE_PATH", dirname(dirname(__DIR__)));

const
YEAR = 31104000,
MONTH = 2592000,
WEEK = 604800,
DAY = 86400,
HOUR = 3600,
MINUTE = 60,
SECONDS = 1;