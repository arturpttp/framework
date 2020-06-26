<?php


namespace Core;


use Core\Entities\Assets;
use Core\Entities\Str;
use Core\Entities\Time;
use Core\Interfaces\Logger;
use Core\System\LogLevel;
use Core\System\Repository;
use Core\System\System;

class Shadowz extends Assets implements Logger
{

    /**
     * instance of the application
     *
     * @var Application
     */
    public static $app;

    /**
     * instance of router class
     *
     * @var Router
     */
    public static $router;

    public $css = [
        BASE_PATH . '/public/css/reset.css',
        BASE_PATH . '/public/css/style.css'
    ];

    public $js = [
        BASE_PATH . '/public/js/script.js'
    ];

    public $images = [];

    /**
     * Shadowz constructor.
     */
    public function __construct()
    {
        if (!isset(self::$app) || !self::$app instanceof Application)
            self::$app = new Application();
        if (!isset(self::$router) || !self::$router instanceof Router)
            self::$router = new Router(System::routes(), self::$app);
        self::$app->setup();
        self::$router->run();
        self::notice("Application {framework} started", ['framework' => 'Shadowz']);
        parent::load();
    }


    public static function log($level, $message, $placeholders = [])
    {
        $levelName = LogLevel::getStringByLevel($level);
        $replacers = [];
        foreach ($placeholders as $key => $value) {
            $replacers["{{$key}}"] = $value;
            $message = new Str($message);
            if ($message->contains("{{$key}}"))
                $message = $message->replace("{{$key}}", $value);
            $message = $message->get();
        }
        $time = Time::now();
        $logMessage = "[{$levelName}] {$time} - {$message}\n";
        $file = fopen(self::getFilePath(), "a+");
        $content = file_get_contents(self::getFilePath());
        fwrite($file, " ");
        fwrite($file, $content . $logMessage);
        fclose($file);
    }

    public static function emergency($message, $placeholders = [])
    {
        self::log(LogLevel::EMERGENCY, $message, $placeholders);
    }

    public static function alert($message, $placeholders = [])
    {
        self::log(LogLevel::ALERT, $message, $placeholders);
    }

    public static function critical($message, $placeholders = [])
    {
        self::log(LogLevel::CRITICAL, $message, $placeholders);
    }

    public static function error($message, $placeholders = [])
    {
        self::log(LogLevel::ERROR, $message, $placeholders);
    }

    public static function warning($message, $placeholders = [])
    {
        self::log(LogLevel::EMERGENCY, $message, $placeholders);
    }

    public static function notice($message, $placeholders = [])
    {
        self::log(LogLevel::NOTICE, $message, $placeholders);
    }

    public static function info($message, $placeholders = [])
    {
        self::log(LogLevel::INFO, $message, $placeholders);
    }

    public static function debug($message, $placeholders = [])
    {
        self::log(LogLevel::DEBUG, $message, $placeholders);
    }

    public static function getFilePath()
    {
        return BASE_PATH . "/app/log.php";
    }

}

function onShutdown() {
    $repositories = Repository::all();
    foreach ($repositories as $repository)
        if ($repository instanceof \Core\Cache\Repository)
            $repository->save();
}

register_shutdown_function('Core\onShutdown');