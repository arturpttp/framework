<?php


namespace Core\Cache\Abstracts\Database;


use Core\Cache\Repository;
use Core\Cache\Store;

abstract class DatabaseStore implements Store
{

    /**
     * @var Repository
     */
    private $repository;

    public function __construct(DatabaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get($key)
    {
        return $this->repository->get($key);
    }

    public function many(array $keys)
    {
        return $this->repository->getMultiple($keys);
    }

    public function put($key, $value, $seconds)
    {
        $this->repository->set($key, $value, $seconds);
    }

    public function putMany(array $values, $seconds)
    {
        $this->repository->setMultiple($values, $seconds);
    }

    public function increment($key, $value = 1)
    {
        $this->repository->increment($key, $value);
    }

    public function decrement($key, $value = 1)
    {
        $this->repository->decrement($key, $value);
    }

    public function forever($key, $value)
    {
        $this->repository->set($key, $value);
    }

    public function forget($key)
    {
        $this->repository->delete($key);
    }

    public function flush()
    {
        $this->repository->clear();
    }

}