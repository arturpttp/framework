<?php


namespace Core\Database;


use PDO;

interface Connectable
{

    public function isConnected(): bool;
    public function connect();
    public function getConnection(): PDO;
    public function cantConnect(\PDOException $error);

}