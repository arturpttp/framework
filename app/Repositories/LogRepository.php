<?php


namespace App\Repositories;


use App\Repositories\Stores\LogStore;
use Core\Cache\Abstracts\AbstractRepository;

class LogRepository extends AbstractRepository
{

    private static $instance = false;

    public function __construct()
    {
        parent::__construct(new LogStore($this));
    }

    public function getFileName()
    {
        return "log";
    }

    public static function getInstance() {
        if (!self::$instance || !self::$instance instanceof LogRepository)
            self::$instance = new LogRepository();
        return self::$instance;
    }

}