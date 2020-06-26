<?php


namespace Core\System;


class LogLevel
{
    const EMERGENCY = 100;
    const ALERT     = 200;
    const CRITICAL  = 300;
    const ERROR     = 400;
    const WARNING   = 500;
    const NOTICE    = 600;
    const INFO      = 700;
    const DEBUG     = 800;

    public static function getStringByLevel($level = 700)
    {
        switch ($level) {
            case self::EMERGENCY:
                return "EMERGENCY";
            break;
            case self::ALERT:
                return "ALERT";
            break;
            case self::CRITICAL:
                return "CRITICAL";
            break;
            case self::ERROR:
                return "ERROR";
            break;
            case self::WARNING:
                return "WARNING";
            break;
            case self::NOTICE:
                return "NOTICE";
            break;
            case self::INFO:
                return "INFO";
            break;
            case self::DEBUG:
                return "DEBUG";
            break;
        }
        return "INFO";
    }

}