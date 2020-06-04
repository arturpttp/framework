<?php


namespace Core\Database;

use PDO;
use PDOException;

class Connection
{

    private $config;
    public $pdo;
    protected $driver;
    protected $host;
    protected $database;

    public function __construct()
    {
        $this->config = require_once __DIR__ . "/../../app/database.php";
        $this->driver = $this->config['driver'];
        $this->host = $this->config[$this->driver]['host'];
        $this->database = $this->config[$this->driver]['database'];
        if ($this->driver == "mysql") {
            $user = $this->config[$this->driver]['user'];
            $password = $this->config[$this->driver]['password'];
            try {
                $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->database}", $user, $password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }catch (PDOException $e) {
                error($e->getMessage());
            }
        }else if ($this->driver == "sqlite") {

        }else {
            error("Database driver is invalid");
        }
    }

}