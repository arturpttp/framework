<?php


namespace Core\Cache\Abstracts\Database;


use Core\Cache\CacheInterface;
use Core\Database\Connection;
use Core\Database\Database;
use Core\Shadowz;

abstract class DatabaseCache implements CacheInterface
{

    protected $connection;
    protected $pdo;

    public function __construct()
    {
        try {
            $this->connection = new Database($this->getTable());
            $this->pdo = $this->connection->pdo;
        } catch (\Exception $e) {
            Shadowz::error($e->getMessage());
        }

    }

    public function get($key, $defaults = null)
    {
        if (!$this->has($key))
            return $defaults;
        $value = $this->connection->find($key);
        return $value->first();
    }

    public function set($key, $value = null, $time = 0)
    {
        $this->connection->insert($key);
    }

    public function delete($key)
    {
        $this->connection->delete($key);
    }

    public function clear($keep = [])
    {
        foreach ($this->all() as $item) {
            if (isset($item['id']))
                $this->delete(['id' => $item['id']]);
        }
    }

    public function getMultiple($keys, $default = null)
    {
        //TODO: getMultiple
    }

    public function setMultiple($values, $times = 0)
    {
        //TODO: setMultiple
    }

    public function deleteMultiple($keys)
    {
        //TODO: deleteMultiple
    }

    public function has($key)
    {
        return $this->connection->contains($key[0], $key[1]);
    }

    public function all()
    {
        return $this->connection->All()->fetch();
    }

    public abstract function getTable();

}