<?php


namespace Core\Database;

use Core\Config;
use PDO;
use PDOException;

class Connection implements Connectable
{

    /**
     * @var Config
     */
    private $config;
    /**
     * @var PDO
     */
    public $pdo;
    protected $driver;
    protected $host;
    protected $database;
    /**
     * @var array
     */
    private $items = [
        'driver' => 'mysql',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_CASE => PDO::CASE_NATURAL,
            PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_EMULATE_PREPARES => false,
        ],
        'mysql' => [
            'host' => 'localhost',
            'database' => 'framework',
            'user' => 'root',
            'password' => ''
        ],

        'sqlite' => [
            'host' => 'database.db',
            'database' => 'storage/database/database.db'
        ]
    ];
    public function __construct()
    {
        $this->connect();
    }

    public function isConnected(): bool
    {
        if ($this->pdo instanceof PDO && !is_null($this->pdo) && !$this->pdo)
            return true;
        else
            return false;
    }

    public function connect()
    {
        $this->config = Config::getConfig($this->items);
        $this->driver = $this->config->get("driver");
        $this->host = $this->config->getByGroup($this->driver, 'host');
        $this->database = $this->config->getByGroup($this->driver, 'database');
        if ($this->driver == "mysql") {
            $user = $this->config->getByGroup($this->driver, 'user');;
            $password = $this->config->getByGroup($this->driver, 'password');
            try {
                $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->database}", $user, $password);
            } catch (PDOException $e) {
                $this->cantConnect($e);
            }
        } else if ($this->driver == "sqlite") {
            die("sqlite is in WIP");
        } else {
            die("Database driver is invalid");
        }
        if ($this->isConnected())
            foreach ($this->config->get("options") as $key => $value) {
                $this->pdo->setAttribute($key, $value);
            }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    public function cantConnect(\PDOException $error)
    {
        if (DEBUG)
            error($error->getMessage());
    }

}