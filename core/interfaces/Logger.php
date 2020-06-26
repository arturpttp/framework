<?php


namespace Core\Interfaces;


interface Logger
{

//    const EMERGENCY = 100;
//    const ALERT     = 200;
//    const CRITICAL  = 300;
//    const ERROR     = 400;
//    const WARNING   = 500;
//    const NOTICE    = 600;
//    const INFO      = 700;
//    const DEBUG     = 800;

    public static function log($level, $message, $placeholders = []);

    public static function emergency($message, $placeholders = []);
    public static function alert($message, $placeholders = []);
    public static function critical($message, $placeholders = []);
    public static function error($message, $placeholders = []);
    public static function warning($message, $placeholders = []);
    public static function notice($message, $placeholders = []);
    public static function info($message, $placeholders = []);
    public static function debug($message, $placeholders = []);

    static function getFilePath();

}